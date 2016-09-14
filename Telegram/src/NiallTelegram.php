<?php
namespace Niall\Telegram;

use GuzzleHttp\Client as GuzzleClient;
use Telegram\Bot\Api as TelegramApi;
use Telegram\Bot\Objects;

class NiallTelegram{
    private $api_key;
    private $bot_name;

    private $username;

    /** @var TelegramApi */
    private $telegram;
    /** @var GuzzleClient  */
    private $guzzle;

    private $updatePointer = 0;
    private $powerWords;

    public function __construct($api_key, $bot_name)
    {
        $this->api_key = $api_key;
        $this->bot_name = $bot_name;

        $this->telegram = new TelegramApi($this->api_key);
        $this->guzzle = new GuzzleClient();
        if(file_exists(APP_ROOT . "/pointer.txt")){
            $this->updatePointer = file_get_contents(APP_ROOT . "/pointer.txt");
        }else {
            $this->updatePointer = 0;
        }
    }

    public function run(){
        $response = $this->telegram->getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();
        $this->username = $response->getUsername();

        echo "Hello, my ID is {$botId} (And my name is {$firstName} / @{$username})\n\n";

        $this->powerWords = [
            'niall',
            '/niall',
            $username,
            $firstName
        ];

        while(true){
            $this->getUpdates();
        }
    }

    private function setUpdatePointer(int $pointer){
        file_put_contents(APP_ROOT . "/pointer.txt", $pointer);
        $this->updatePointer = $pointer;
        return $this;
    }

    private function getUpdates(){

        $updates = $this->telegram->getUpdates([
            'offset' => $this->updatePointer + 1,
            'limit' => 5,
        ]);
        if(count($updates) > 0) {
            foreach ($updates as $update) {
                /** @var $update Objects\Update */
                if ($update->getUpdateId() > $this->updatePointer) {
                    $this->setUpdatePointer($update->getUpdateId());
                }
                if ($update->getMessage()) {
                    $this->parseMessage($update->getMessage());
                }
            }
        }else{
            sleep(5);
        }
    }

    private function containsPowerWords(string $message, array $powerWords){
        foreach($powerWords as $powerWord){
            if(stripos($message, $powerWord) !== false){
                return true;
            }
        }
        return false;
    }

    private function parseMessage(Objects\Message $message){
        if(
            $message->getText() &&
            $message->getDate() >= strtotime("an hour ago")
        ){
            if($this->containsPowerWords($message->getText(), $this->powerWords)) {
                echo "Message: {$message->getChat()->getTitle()}/{$message->getChat()->getUsername()} said {$message->getText()}\n";
                $response = $this->guzzle->request(
                    'POST',
                    NIALL_INSTANCE . "/v1/speak", [
                        'json' => [
                            'Message' => trim($message->getText())
                        ],
                        'headers' => [
                            'User-Agent' => 'niall-telegram/1.0',
                            'Accept' => 'application/json',
                        ]
                    ]
                );

                if ($response->getStatusCode() == 200) {
                    $json = json_decode($response->getBody()->getContents());
                    echo "Reply: {$json->reply}\n";
                    $this->telegram->sendMessage([
                        'chat_id' => $message->getChat()->getId(),
                        'text' => $json->reply,
                        'reply_to_message_id' => $message->getMessageId(),
                    ]);
                }
                echo "\n";
            }else{
                $response = $this->guzzle->request(
                    'POST',
                    NIALL_INSTANCE . "/v1/listen", [
                        'json' => [
                            'Message' => trim($message->getText())
                        ],
                        'headers' => [
                            'User-Agent' => 'niall-telegram/1.0',
                            'Accept' => 'application/json',
                        ]
                    ]
                );
            }
        }
    }




}