<?php

namespace App\Console\Commands;


use App\Services\User\IChatsService;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Predis\Client;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    public $redisPrefix = "";
    public $publisher;
    public $subscribe   = [
        'request_page',
        'btm_select_2_search',
        'send_chat_msg'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->redisPrefix = env('REDIS_PREFIX');

        $redis           = Redis::connection('subscriber');
        $this->publisher = new Client([
            "port" => env("REDIS_PORT")
        ]);


        $publisher = $this->publisher;

        $redis->subscribe($this->subscribe, function ($message) use ($publisher) {

            $message = json_decode($message);

            if (!is_object($message)) {
                return;
            }

            if ($message->type == "send_chat_msg") {
                return $this->send_chat_msg($message);
            }


            return $this->handleHttpRequest($publisher, $message);


        });

    }


    private function send_chat_msg($message)
    {

        $request                              = app('request');
        $request["message"]                   = $message->message;
        $request["this_chat_members_enc_ids"] = $message->this_chat_members_enc_ids;

        $user = User::getUserByEncId($message->this_user_enc_id);

        $service = app(IChatsService::class);
        $service->sendMessage($request,$user,$message->chat_enc_id);

    }

    #region handleHttpRequest

    private function handleHttpRequest($publisher, $message)
    {

        $url = $message->url;
        $url = str_replace(url("/"), "", $url);

        $request               = app('request');
        $request["load_inner"] = true;
        $matchRequest          = $request->create($url);

        foreach ($matchRequest->all() as $key => $value) {
            $request[$key] = $value;
        }

        try {
            $route = app('router')->getRoutes()->match($matchRequest);
        } catch (\Exception $e) {
        }


        if (!isset($route->action)) {
            return "";
        }

        if (!isset($route->action["controller"])) {
            return "";
        }

        $html = \App::call($route->action["controller"]);

        if (is_a($html, \View::class)) {
            $html = $html->render();
        }

        $returnMsg = [
            'socket_id' => $message->socket_id,
            'html'      => $html,
            'href'      => url($url),
        ];

        if (isset($message->request->btm_select_2_search)) {
            $returnMsg["btm_select_2_search_is_done"] = 1;
            $returnMsg["element_class"]               = $message->request->element_class;
        }

        $publisher->publish($this->redisPrefix . 'html_page_is_done', json_encode($returnMsg));

    }

    private function sendParamsToMethodAtController($url, $params)
    {


        $url = str_replace(url("/"), "", $url);

        $request = app('request');

        foreach ($params as $key => $value) {
            $request[$key] = $value;
        }

        $matchRequest = $request->create($url);

        try {
            $route = app('router')->getRoutes()->match($matchRequest);
        } catch (\Exception $e) {
        }


        if (!isset($route->action)) {
            return "";
        }

        if (!isset($route->action["controller"])) {
            return "";
        }

        $html = \App::call($route->action["controller"]);

        return $html;

    }

    #endregion


}
