<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemDep;
use App\DepPromo;
use App\User;
use App\ActivePromo;
use App\Setting;
use App\Status;
use App\Payment;
use App\Services\DepositService;
use Illuminate\Support\Facades\Redis;

class PaymentController extends Controller
{
	protected $depositService;

	public function __construct()
	{
		parent::__construct();
		$this->redis = Redis::connection();
		$this->depositService = new DepositService();
	}

	public function resultLinePay(Request $r){
		$setting = Setting::first();

		$linepay_id = $setting->linepay_id;
		$linepay_secret_2 = $setting->linepay_secret_2;

		$m_id = $linepay_id;
		$m_secret_2 = $linepay_secret_2;

		$order_id = $r->order_id;
		$amount = $r->amount;
		$sign = $r->sign;

		$_sign = md5($m_id.'|'.$m_secret_2.'|'.$amount.'|'.$order_id);

		// Проверка IP
		if (self::getClientIP() != '45.142.122.86') {
			echo "ip is ".self::getClientIP();
		}

		if ($sign != $_sign) {
			die("wrong sign");
		}

		return $this->depositService->processDeposit($order_id, (float)$amount);
	}
	public function resultPaypalych(){
		$setting = Setting::first();

		$paypaylych_id = $setting->paypaylych_id;
		$paypaylych_token = $setting->paypaylych_token;

		$OutSum = $_GET['OutSum'] ?? 0;
		$InvId = $_GET['InvId'] ?? 0;
		$SignatureValue = $_GET['SignatureValue'] ?? '';

		$sign = strtoupper(md5($OutSum . ":" . $InvId . ":" . $paypaylych_token));

		if($sign != $SignatureValue){
			die('wrong sign');
		}

		return $this->depositService->processDeposit($InvId, (float)$OutSum);
	}

	public function resultFK(Request $r){
		$setting = Setting::first();

		$merchant_id = $setting->fk_id;
		$secret_word = $setting->fk_secret_2;

		$sign = md5($merchant_id.':'.$r->AMOUNT.':'.$secret_word.':'.$r->MERCHANT_ORDER_ID);

		if ($sign != $r->SIGN) die('wrong sign');

		return $this->depositService->processDeposit($r->MERCHANT_ORDER_ID, (float)$r->AMOUNT);
	}

	public function resultRukassa(Request $r){
		return $this->depositService->processDeposit($r->order_id, (float)$r->amount);
	}

	public function resultExwave(){
		$entity_body = file_get_contents('php://input');
		$r = json_decode($entity_body, true);

		if (!$r || !isset($r['pay_id'], $r['amount'])) {
			die('Invalid request');
		}

		return $this->depositService->processDeposit($r['pay_id'], (float)$r['amount']);
	}

	public function resultRubpay(Request $r){
		$hash = md5("1127" . $r->order_id . $r->payment_id . $r->amount . $r->currency . $r->status . "7a7673d6ac1954015da6d344beeeff7e");
        if($hash != $_POST['hash']) die("wrong sign");

		return $this->depositService->processDeposit($r->order_id, (float)$r->amount);
	}

	public function resultQpay(Request $r){
		return $this->depositService->processDeposit($r->order, (float)$r->sum);
	}

	public function resultPiastrix(Request $r){
		$allowedIPs = ['51.68.53.104', '51.68.53.105', '51.68.53.106', '51.68.53.107', '37.48.108.180', '37.48.108.181'];
		if (!in_array(self::getClientIP(), $allowedIPs)) {
			die("hacking attempt! " . self::getClientIP());
		}

		$payment = Payment::where('transaction', $r->shop_order_id)->first();
		if (!$payment || $payment->status == 1) {
			die('Ошибка');
		}

		return $this->depositService->processDeposit($r->shop_order_id, (float)$payment->sum);
	}

	public function result(){
		$setting = Setting::first();

		$unique_id = $_GET['unique_id'] ?? '';
		$sign = $_GET['sign'] ?? '';
		$amount = $_GET['amount'] ?? 0;

		$my_token = $setting->gamepay_api_key;
		$my_shop_id = $setting->gamepay_shop_id;
		$amountFormatted = number_format($amount, 2, '.', '');
		$my_sign = hash('sha256', "{$unique_id}:{$amountFormatted}:{$my_token}:{$my_shop_id}");

		if ($sign != $my_sign) {
			die("Недействительная подпись");
		}

		return $this->depositService->processDeposit($unique_id, (float)$amount);
	}

	/**
	 * Получить IP клиента с учетом CloudFlare прокси.
	 */
	protected static function getClientIP(): string {
		return $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
	}

	public function requestGamePay($type, $params){
		$url = 'https://oplatalift.site/api/'.$type; 

		$result = file_get_contents($url, false, stream_context_create(array( 
			'http' => array( 
				'method' => 'POST', 
				'header' => 'Content-type: application/x-www-form-urlencoded', 
				'content' => http_build_query($params) 
			) 
		))); 

		$response = json_decode($result, true); 
		return $response;
	}
	public function checkStatus(Request $r){
		$id = $r->id;
		$setting = Setting::first();
		$params = array( 
			'vip_id' => 4,
			'order_id'=> $id,
			'token'=> $setting->gamepay_api_key
		);
		$resp = self::requestGamePay('checkStatus', $params);
		$status = $resp['data']['status'];
		if($status == 0){
			return response(['success' => false, 'mess' => 'Перевод не найден']);
		}
		return response(['success' => true]);
	}
	public function go(Request $r){
		$sum = $r->sum;
		$system = $r->system;
		$promo = $r->promo;	

		if(!is_numeric($sum)){
			return response(['success' => false, 'mess' => 'Введите корректно сумму пополнения']);
		}

		if(\Auth::guest()){return response(['success' => false, 'mess' => 'Авторизуйтесь' ]);}

		$user = \Auth::user();
if($user->type_balance == 1){
            return response(['success' => false, 'mess' => 'Переключитесь на реальный баланс']);
        }
		$countSystemDep = SystemDep::where('id', $system)->count();
		if($countSystemDep == 0){
			return response(['success' => false, 'mess' => 'Ошибка']);
		}

		//if($user->admin == 3) return response(['success' => false, 'mess' => 'Ошибка']);

		$systemDep = SystemDep::where('id', $system)->first();
		$minDep = $systemDep->min_sum;
		$psDep = $systemDep->ps;
		$number_ps = $systemDep->number_ps;
		$img = $systemDep->img;

		if($sum < $minDep){
			return response(['success' => false, 'mess' => "Минимальная сумма пополнения {$minDep}р."]);
		}

		$percent = 0;

		if($promo != ''){
			$deppromo_count = DepPromo::where('name', $promo)->count();
			if($deppromo_count == 0){
				return response(['success' => false, 'mess' => 'Промокод не найден или закончился' ]);
			}

			$promo_act_count = ActivePromo::where('promo', $promo)->where('user_id', $user->id)->count();
			if ($promo_act_count > 0)  {   
				return response(['success' => false, 'mess' =>  "Вы уже использовали этот код"]);
			}
			$deppromo = DepPromo::where('name', $promo)->first();
			$start = $deppromo->start;
			$end = $deppromo->end;
	        $active = $deppromo->active;//ALL
	        $actived = $deppromo->actived;
	        $percent = $deppromo->percent;
	        $now_time = time();
	        $start = strtotime($start);
	        $end = strtotime($end);

	        if($actived == $active){
	        	return response(['success' => false, 'mess' => 'Промокод не найден или закончился' ]);
	        }

	        if($now_time < $start){
	        	return response(['success' => false, 'mess' => 'Промокод будет доступен '.date('d.m в H:i', $start) ]);
	        }


	        if($now_time > $end){
	        	return response(['success' => false, 'mess' => 'Промокод не найден или закончился' ]);
	        }

	        $deppromo->actived += 1;
        	$deppromo->save();

        	ActivePromo::create(array(
            'promo'  => $promo,
            'user_id'=> $user->id,
            'type_promo' => 1,
            'promo_id' => $deppromo->id,
        ));

    }

    $setting = Setting::first();

    $unique_id = time() * $user->id;
    $modal = 0;
    $transfer = 'false';
    if($psDep == 1){
			// FreeKassa
    	$merchant_id = $setting->fk_id;
    	$secret_word = $setting->fk_secret_1;
    	$order_id = $unique_id;
    	$order_amount = $sum;
    	$currency = 'RUB';
    	$sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$currency.':'.$order_id);
    	
    	$link = "https://pay.freekassa.ru?m=".$merchant_id."&oa={$order_amount}&o={$order_id}&currency=RUB&s=".$sign."";
    }

    if($psDep == 2){
		$curl = curl_init();
		curl_setopt_array($curl, [
  			CURLOPT_URL => 'https://api.qpay.su/v1/deposit',
  			CURLOPT_RETURNTRANSFER => true,
 			CURLOPT_CUSTOMREQUEST => 'POST',
  			CURLOPT_POSTFIELDS => json_encode([
    			'order' => (string) $unique_id,
    			'type' => 'sum',
    			'format' => 'json',
    			'method' => 'mnl',
    			'sum' => $sum
  			]),
  			CURLOPT_HTTPHEADER => [
    			'Authorization: Bearer',
    			'Content-Type: application/json'
  			],
		]);
		$response = json_decode(curl_exec($curl));
		curl_close($curl);

		$arub = floor($response->data->sum);
		$acop = ($response->data->sum - $arub) * 100;

		$link = "https://qiwi.com/payment/form/99?extra%5B%27account%27%5D=". $response->data->person ."&amountInteger=". $arub ."&amountFraction=". $acop ."&currency=643&blocked[0]=sum&blocked[1]=account";
    }

	if($psDep == 4){
		// Rukassa
		$data = [
            'shop_id'	=> 486,
            'token'		=> '',
            'order_id' 	=> $unique_id,
            'amount' 	=> $sum,
            'method' => $number_ps == 0 ? 'card' : ($number_ps == 1 ? 'sbp' : 'crypta')
        ];

		$ch = curl_init('https://lk.rukassa.pro/api/v1/create');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        $link = $result->url;
	}

	if($psDep == 5){
		$url = "https://api.exwave.io/create/";
        $dataFields = array(
            "method" => $number_ps == 0 ? 'card' : ($number_ps == 1 ? 'qiwi' : 'USDTTRC20'),
            "order_id" => $unique_id,
            "amount" => $sum,
            "token" => ""
        );

		$result = json_decode(file_get_contents($url . "?" . http_build_query($dataFields)));

		$link = $result->url;
	}

	if($psDep == 6){
		$payload = http_build_query([
			'project_id' => 1127,
			'amount' => $sum,
			'order_id' => $unique_id,
			'sign' => md5("". "1127" . $unique_id . $sum . "1" . ""),
			'payment_method' => $number_ps
		]);

		$link = "https://rubpay.ru/pay/create?". $payload;
	}

    Payment::create(array(
    	'user_id' => $user->id,
    	'login' => $user->name, 
    	'avatar' => $user->avatar,
    	'sum' => $sum,
    	'data' => date('d.m.Y H:i'),
    	'transaction' => $unique_id,
    	'beforepay' => $user->balance,
    	'percent' => $percent,
    	'img_system' => $img
    ));

    return response(['success' => true, 'link' => $link, 'modal' => $modal, 'transfer' => $transfer, 'img' => $img]);
}


}
