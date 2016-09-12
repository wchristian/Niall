<?php

namespace Niall\Controllers;

use Niall\Abstracts\Controller;
use Niall\Mind\Niall as NiallMind;
use Slim\Http\Request;
use Slim\Http\Response;
use Niall\Mind\Models;

class ResponseController extends Controller{

    /** @var NiallMind  */
    private $niallMind;

    public function __construct(NiallMind $niall)
    {
        $this->niallMind = $niall;
    }

    public function doResponse(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $newWords = [];

        if(isset($body['Message'])){
            $sentances = [];
            if(is_string($body['Message'])){
                $messages = [$body['Message']];
            }else{
                $messages = $body['Message'];
            }
            foreach($messages as $message){
                $lines = explode(".", $message);
                $lines = array_filter($lines);
                $sentances = array_merge($sentances, $lines);
            }
            foreach($sentances as &$sentance){
                $sentance = trim($sentance);
                $newWords = array_merge($newWords, $this->niallMind->niall_parse_message($sentance));
            }
        }

        /** @var $words Models\NiallWord[] */
        list($thing, $words) = $this->niallMind->get_sentence();
        $outputWords = [];
        foreach ($words as $word) {
            $outputWords[] = [
                'word' => $word->word,
                'score' => intval($word->score),
                'langs' => $word->getLanguageList()
            ];
        }
        $language = 'en';

        return $response->withJson([
            "reply" => $thing,
            "language" => $language,
            "words" => $outputWords,
            'new_words' => $newWords,
            'response_time' => number_format(microtime(true) - APP_START_MICROTIME, 3) . " sec",
        ]);
    }
}