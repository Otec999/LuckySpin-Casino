<?php


namespace App\Classes;


use Illuminate\Support\Facades\Log;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;
use App\User;
use App\Repost;
use App\UserRepost;
use App\Posts;
class ServerHandler extends VKCallbackApiServerHandler
{
    private $secret = '';
    private $groupId = 0;
    private $confirmationToken = '';

    protected $chatId;
    protected $text;

    public function __construct() {
        parent::__construct();
        $this->secret = env('VK_SECRET', '');
        $this->groupId = (int)env('VK_GROUP_ID', 0);
        $this->confirmationToken = env('VK_CONFIRMATION_TOKEN', '');
    }

    function confirmation(int $group_id, ?string $secret)
    {
        Log::info(print_r($group_id, true));
        if ($secret === $this->secret && $group_id === $this->groupId) {
            echo $this->confirmationToken;
        }
    }

    public function wallPostNew(int $group_id, ?string $secret, array $object)
    {
        
        $postId = $object["id"];
        $postType = $object["post_type"];

        if($postType == 'post'){
            $this->sendMessage(451073906, "Новый пост в группе");
            Posts::create(array(
                'post_id' => $postId
            ));
        }

        echo 'ok';
    }
    public function wallRepost(int $group_id, ?string $secret, array $object)
    {        

        $this->userId = $object["from_id"];
        $this->postId = $object["copy_history"][0]->id;

        $user = User::where('vk_id', $this->userId)->first();
        $user_repost = UserRepost::where('post_id', $this->postId)->where('user_id', $user->id)->count();

        $posts_min_id = Posts::max('id') - 25;
        if($posts_min_id < 0){
            $posts_min_id = 1;
        }
        $posts = Posts::where('post_id', $this->postId)->count();
        if($posts == 0){
            $user_repost = 1;
        }else{
            $post = Posts::where('post_id', $this->postId)->first();
            if($post->id < $posts_min_id){
                $user_repost = 1;
            }
        }
        if($user_repost == 0){
        	UserRepost::create(array(
        		'user_id' => $user->id,
        		'post_id' => $this->postId
        	));
            // далее 10 100 200
            $reposts = $user->reposts;
            $balance_repost = $user->balance_repost;

            $newreposts = $reposts + 1;
            $count_repost_level = Repost::where('repost_from', '<=', $newreposts)->where('repost_to', '>=', $newreposts)->count();
            if($count_repost_level == 0){
                $repost_level = Repost::orderBy('id', 'desc')->first();
            }else{
                $repost_level = Repost::where('repost_from', '<=', $newreposts)->where('repost_to', '>=', $newreposts)->first();
            }

           
            $bonus = $repost_level->bonus;
           
           

            $new_balance_repost = $balance_repost + $bonus;

            $user->reposts = $newreposts;
            $user->balance_repost = $new_balance_repost;
            $user->save();

            $this->sendMessage(451073906, "Новый репост #".$this->postId."! От @id".$this->userId," (@id".$this->userId.")");
        }
        

         echo 'ok';
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        Log::info(print_r($object, true));


        $this->chatId = $object["peer_id"];
        $this->text = $object["text"];

        $arr = [
            [
                "key" => "привет",
                "func" => function () {
                    $this->sendMessage($this->chatId, "Привет, Друг!");
                },
            ],
            [
                "key" => "как дела",
                "func" => function () {
                    $this->sendMessage($this->chatId, "Норм, а у тебя?");
                    },
            ],

            [
                "key" => "хай",
                "func" => function () {
                    $this->sendMessage($this->chatId, "Нихао!");
                },
            ]

        ];

        $is_found = false;
        foreach ($arr as $item) {

            $tmp = mb_strtolower($this->text);

            Log::info("$tmp =>");
            if (strpos($tmp, $item["key"]) !==false) {
                $item["func"]();
                $is_found = true;
                //break;
            }
        }

        if (!$is_found)
            //$this->sendMessageWithKeyboard($this->chatId, "Я тебя не понимаю!(");

        //$this->sendMessageWithKeyboard($this->chatId,"Спасибо! Ваше сообщение: $this->text ");
        echo 'ok';
    }

    protected function sendMessage($chatId, $message)
    {
        $access_token = env('VK_ACCESS_TOKEN', '');
        $vk = new VKApiClient();
        $vk->messages()->send($access_token, [
            'peer_id' => $chatId,
            'message' => $message,
            'random_id' => random_int(0, 10000000000),

        ]);
    }

    protected function sendMessageWithKeyboard($chatId, $message)
    {
        $access_token = env('VK_ACCESS_TOKEN', '');
        $vk = new VKApiClient();
        $vk->messages()->send($access_token, [
            'peer_id' => $chatId,
            'message' => $message,
            'random_id' => random_int(0, 10000000000),
            'keyboard' => json_encode([
                "one_time"=>false,
                "buttons"=>[
                    [
                        [
                            "action"=>[
                                "type"=>"text",
                                "payload"=>"{\"button\":\"привет\"}",
                                "label"=>"Привет!"
                            ],
                            "color"=>"positive"
                        ],

                        [
                            "action"=>[
                                "type"=>"text",
                                "payload"=>"{\"button\":\"прощай\"}",
                                "label"=>"Прощай!"
                            ],
                            "color"=>"negative"
                        ]
                    ],[
                        [
                            "action"=>[
                                "type"=>"text",
                                "payload"=>"{\"button\":\"как дела\"}",
                                "label"=>"Как дела!"
                            ],
                            "color"=>"secondary"
                        ],
                    ]
                ]
            ])

        ]);
    }
}