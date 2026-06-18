 @extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('admin.components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent

@php
$setting = \App\Setting::first();
@endphp



<div class="row">
	
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<h3>Настройки сайта</h3>
				<div class="row">
				    <div class="col-lg-3 mb-3">
				        <label>Название сайта</label>
				        <input type="" class="form-control" id="name" value="{{$setting->name}}" name="">
				    </div>
				    <div class="col-lg-2 mb-3">
				        <label>Валюта сайта</label>
				        <select class="form-select" id="currency" onchange="changeCurrency()">
				            <option value="RUB" data-rate="1" data-symbol="₽" @if($setting->currency == 'RUB') selected @endif>₽ RUB (Россия)</option>
				            <option value="AZN" data-rate="27" data-symbol="₼" @if($setting->currency == 'AZN') selected @endif>₼ AZN (Азербайджан)</option>
				            <option value="USD" data-rate="90" data-symbol="$" @if($setting->currency == 'USD') selected @endif>$ USD (США)</option>
				            <option value="TRY" data-rate="3.5" data-symbol="₺" @if($setting->currency == 'TRY') selected @endif>₺ TRY (Турция)</option>
				            <option value="UAH" data-rate="2.5" data-symbol="₴" @if($setting->currency == 'UAH') selected @endif>₴ UAH (Украина)</option>
				        </select>
				        <small class="text-muted">1 RUB ≈ сколько в вашей валюте</small>
				    </div>
				    <div class="col-lg-2 mb-3">
				        <label>Курс к RUB</label>
				        <input type="number" step="0.0001" class="form-control" id="currency_rate" value="{{$setting->currency_rate ?? 1}}" readonly>
				        <small class="text-muted">Авто-подставляется при выборе валюты</small>
				    </div>
				    <div class="col-lg-2 mb-3">
				        <label>Символ валюты</label>
				        <input type="" class="form-control" id="currency_symbol" value="{{$setting->currency_symbol ?? '₽'}}" readonly>
				    </div>
					<div class="col-lg-3 mb-3">
						<label>Айди группы вк</label>
						<input type="" class="form-control" id="group_id" value="{{$setting->group_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Токен группы вк</label>
						<input type="" class="form-control" id="group_token" value="{{$setting->group_token}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Канал тг</label>
						<input type="" class="form-control" id="tg_id" value="{{$setting->tg_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Бот тг</label>
						<input type="" class="form-control" id="tg_bot_id" value="{{$setting->tg_bot_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3" >
						<label>Токен бота тг</label>
						<input type="" class="form-control" id="tg_token" value="{{$setting->tg_token}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Бонус за регистрацию</label>
						<input type="" class="form-control" id="bonus_reg" value="{{$setting->bonus_reg}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Бонус за подписку на группу ВК и ТГ</label>
						<input type="" class="form-control" id="bonus_group" value="{{$setting->bonus_group}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Максимальный вывод с бонуса</label>
						<input type="" class="form-control" id="max_withdraw_bonus" value="{{$setting->max_withdraw_bonus}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Депозит для перевода средств</label>
						<input type="" class="form-control" id="dep_transfer" value="{{$setting->dep_transfer}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Депозит для создания промокода</label>
						<input type="" class="form-control" id="dep_createpromo" value="{{$setting->dep_createpromo}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Тема сайта</label>
						<select class="form-select" id="theme">
                            <option value="0" @if($setting->theme == 0) selected="selected" @endif>Обычная</option>
                            <option value="1" @if($setting->theme == 1) selected="selected" @endif>Новогодняя</option>
                        </select>
						
					</div>
					<div class="col-lg-6 mb-3">
						<label>Мета-теги</label>
						<textarea type="" class="form-control" id="meta_tags" name="">{{$setting->meta_tags}}</textarea>
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(1)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
								<h3>Настройки платежной системы FreeKassa</h3>
				<div class="row">
					<div class="col-lg-12 mb-3">
						<div class="alert alert-success">
							<strong>✅ FreeKassa — ЛУЧШИЙ ВАРИАНТ ДЛЯ АЗЕРБАЙДЖАНА</strong><br>
							1. Зарегистрируйтесь на <a href="https://freekassa.ru" target="_blank">FreeKassa.ru</a><br>
							2. В личном кабинете создайте магазин → получите ID, SECRET 1, SECRET 2<br>
							3. В настройках магазина укажите URL для уведомлений: <code>{{ url('/deposit/resultfk') }}</code><br>
							4. Введите данные ниже и нажмите "Сохранить"<br>
							<strong>Игроки смогут пополнять картами AZN, QIWI, USDT</strong>
						</div>
					</div>
					<div class="col-lg-3 mb-3">
						<label>FK ID (Merchant ID)</label>
						<input type="" class="form-control" id="fk_id" value="{{$setting->fk_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>FK SECRET 1</label>
						<input type="" class="form-control" id="fk_secret_1" value="{{$setting->fk_secret_1}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>FK SECRET 2</label>
						<input type="" class="form-control" id="fk_secret_2" value="{{$setting->fk_secret_2}}" name="">
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(2)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<h3>Настройки платежной системы PrimePayments</h3>
				<div class="row">
					<div class="col-lg-12 mb-3">
						<div class="alert alert-info">
							<strong>ℹ️ PrimePayments — для карт Visa/Mastercard (в т.ч. AZN)</strong><br>
							1. Зарегистрируйтесь на <a href="https://primepayments.com" target="_blank">PrimePayments.com</a><br>
							2. Получите ID проекта, SECRET 1, SECRET 2<br>
							3. URL уведомлений: <code>{{ url('/deposit/resultprime') }}</code><br>
						</div>
					</div>
					<div class="col-lg-3 mb-3">
						<label>ID проекта</label>
						<input type="" class="form-control" id="prime_id" value="{{$setting->prime_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>SECRET 1</label>
						<input type="" class="form-control" id="prime_secret_1" value="{{$setting->prime_secret_1}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>SECRET 2</label>
						<input type="" class="form-control" id="prime_secret_2" value="{{$setting->prime_secret_2}}" name="">
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(4)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>
				</div>
			</div>
		</div>
	</div>
						<label>Действие</label>
						<button onclick="saveSetting(3)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<h3>Настройки платежной системы Primepayments</h3>
				<div class="row">
					<div class="col-lg-3 mb-3">
						<label>ID проекта</label>
						<input type="" class="form-control" id="prime_id" value="{{$setting->prime_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>SECRET 1</label>
						<input type="" class="form-control" id="prime_secret_1" value="{{$setting->prime_secret_1}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>SECRET 2</label>
						<input type="" class="form-control" id="prime_secret_2" value="{{$setting->prime_secret_2}}" name="">
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(4)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<h3>Настройки платежной системы Linepay</h3>
				<div class="row">
					<div class="col-lg-3 mb-3">
						<label>ID проекта</label>
						<input type="" class="form-control" id="linepay_id" value="{{$setting->linepay_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>SECRET 1</label>
						<input type="" class="form-control" id="linepay_secret_1" value="{{$setting->linepay_secret_1}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>SECRET 2</label>
						<input type="" class="form-control" id="linepay_secret_2" value="{{$setting->linepay_secret_2}}" name="">
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(5)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>


				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<h3>Настройки платежной системы Paypaylych</h3>
				<div class="row">
					<div class="col-lg-3 mb-3">
						<label>ID проекта</label>
						<input type="" class="form-control" id="paypaylych_id" value="{{$setting->paypaylych_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Токен</label>
						<input type="" class="form-control" id="paypaylych_token" value="{{$setting->paypaylych_token}}" name="">
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(6)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>


				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<h3>Настройки платежной системы AezaPay</h3>
				<div class="row">
					<div class="col-lg-3 mb-3">
						<label>ID проекта</label>
						<input type="" class="form-control" id="aezapay_id" value="{{$setting->aezapay_id}}" name="">
					</div>
					<div class="col-lg-3 mb-3">
						<label>Private Key</label>
						<input type="" class="form-control" id="aezapay_token" value="{{$setting->aezapay_token}}" name="">
					</div>
					<div class="col-lg">
						<label>Действие</label>
						<button onclick="saveSetting(7)" class="btn btn-info btn-block w-100">Сохранить</button>
					</div>


				</div>
			</div>
		</div>
	</div>


</div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h3>💰 Автовывод прибыли (Profit Withdraw)</h3>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label>Тип кошелька</label>
                        <select class="form-select" id="profit_wallet_type">
                            <option value="qiwi" @if($setting->profit_wallet_type == "qiwi") selected @endif>QIWI</option>
                            <option value="yoomoney" @if($setting->profit_wallet_type == "yoomoney") selected @endif>ЮMoney</option>
                            <option value="usdt" @if($setting->profit_wallet_type == "usdt") selected @endif>USDT (TRC20)</option>
                            <option value="btc" @if($setting->profit_wallet_type == "btc") selected @endif>BTC</option>
                        </select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Номер кошелька / адрес</label>
                        <input type="text" class="form-control" id="profit_wallet_address" value="{{$setting->profit_wallet_address ?? ''}}" placeholder="+7XXXXXXXXXX">
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label>Порог вывода (₽)</label>
                        <input type="number" class="form-control" id="profit_withdraw_threshold" value="{{$setting->profit_withdraw_threshold ?? 500}}">
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label>Автовывод</label>
                        <select class="form-select" id="profit_auto_withdraw">
                            <option value="0" @if(($setting->profit_auto_withdraw ?? 0) == 0) selected @endif>Выключен</option>
                            <option value="1" @if(($setting->profit_auto_withdraw ?? 0) == 1) selected @endif>Включён</option>
                        </select>
                    </div>
                                        <div class="col-lg-12 mb-3 mt-3">
                        <div class="alert alert-info">
                            <strong>Текущая прибыль:</strong> 
                            🎲 Dice: <b>{{showPrice($setting->dice_profit)}}</b> | 
                            💣 Mines: <b>{{showPrice($setting->mines_profit)}}</b> | 
                            🎡 X30: <b>{{showPrice($setting->wheel_profit)}}</b> | 
                            📈 Crash: <b>{{showPrice($setting->crash_profit)}}</b>
                            <br><strong>Всего к выводу: <span id="totalProfit">{{showPrice($setting->dice_profit + $setting->mines_profit + $setting->wheel_profit + $setting->crash_profit)}}</span></strong>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label>Действие</label>
                        <button onclick="saveProfitSettings()" class="btn btn-info btn-block w-100">Сохранить настройки</button>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label>&nbsp;</label>
                        <button onclick="withdrawProfitManually()" class="btn btn-success btn-block w-100">Вывести сейчас</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>📜 История выплат</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                    <th>Кошелёк</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody id="profitHistoryTable">
                                <tr>
                                    <td colspan="4" class="text-center">Загрузка...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<!-- Profit Withdraw JS -->
<script src="/js/profit_withdraw.js?v={{time()}}"></script>
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>
@endsection
