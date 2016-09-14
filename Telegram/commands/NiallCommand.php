<?php

namespace Longman\TelegramBot\Commands;

use duncan3dc\Speaker\Providers\VoxygenProvider;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\Contact;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class NiallCommand extends Command
{
    protected $name = 'niall';                      //your command's name
    protected $description = 'Say something to Niall'; //Your command description
    protected $usage = '/niall';                    // Usage of your command
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = true;

    private function httpPost($url, $params)
    {
        $postData = '';
        //create name value pairs seperated by &
        foreach ($params as $k => $v) {
            $postData .= $k . '='.$v.'&';
        }
        $postData = rtrim($postData, '&');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;

    }

    private function acapella_group_voice($text, $voice = "Harry"){
        $response = $this->httpPost(
            "http://www.acapela-group.com/demo-tts/DemoHTML5Form_V2.php",
            [
                'MyLanguages' => "sonid15",
                'MySelectedVoice' => $voice,
                'MyTextForTTS' => $text,
                'SendToVaaS' => '',
                't' => 1,
            ]
        );

        $a = explode("var myPhpVar = '", $response, 2);
        $b = explode("';", $a[1], 2);
        $url = $b[0];
        return $url;
    }

    public function execute()
    {
        /** @var Message $message */
        $message = $this->getMessage();

        /** @var Chat $chat */
        $chat = $message->getChat();
        /** @var User $from */
        $from = $message->getFrom();
        $username = $from->getUsername();
        $channel = $chat->getTitle();
        $chat_id = $chat->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);

        $data = array();
        $data['chat_id'] = $chat_id;
        $data['reply_to_message_id'] = $message_id;
        if (empty(trim($text))) {
            $data['text'] = "Well, are you gonna say anything? For example: \"/niall ask me a question\".";
            return Request::sendMessage($data);
        } else {
            echo "{$username}@{$channel} => $text\n";
            $response = $this->httpPost(NIALL_INSTANCE . "/v1/speak", ["Message" => $text]);
            if (json_decode($response)) {
                $response = json_decode($response);
                echo "{$username}@{$channel} <= {$response->reply}\n";
                if(rand(0,1) == 1 && strlen($response->reply) < 100){

                    $rand = rand(1000,9999);
                    $mp3path = "/tmp/niall.{$rand}.mp3";

                    $url = $this->acapella_group_voice($response->reply);
                    $voice = file_get_contents($url);
                    file_put_contents($mp3path, $voice);
                    $return = Request::sendVoice($data, $mp3path);
                    unset($mp3path);
                    return $return;
                }else {
                    $data['text'] = $response->reply;
                    return Request::sendMessage($data);
                }
            } else {
                $data['text'] = "Niall Brain Disconnected ·_. I r dum.";
                return Request::sendMessage($data);
            }
        }
    }
}
