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

    public function doSpeak(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $newWords = [];

        if(isset($body['Message'])){
            $sentences = [];
            if(is_string($body['Message'])){
                $messages = [$body['Message']];
            }else{
                $messages = $body['Message'];
            }
            foreach($messages as $message){
                $lines = explode(".", $message);
                $lines = array_filter($lines);
                $sentences = array_merge($sentences, $lines);
            }
            foreach($sentences as &$sentence){
                $sentence = trim($sentence);
                $newWords = array_merge($newWords, $this->niallMind->niall_parse_message($sentence));
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

    public function doListen(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $newWords = [];

        if(isset($body['Message'])){
            $sentences = [];
            if(is_string($body['Message'])){
                $messages = [$body['Message']];
            }else{
                $messages = $body['Message'];
            }
            foreach($messages as $message){
                $lines = explode(".", $message);
                $lines = array_filter($lines);
                $sentences = array_merge($sentences, $lines);
            }
            foreach($sentences as &$sentence){
                $sentence = trim($sentence);
                $newWords = array_merge($newWords, $this->niallMind->niall_parse_message($sentence));
            }
        }

        return $response->withJson([
            'new_words' => $newWords,
            'response_time' => number_format(microtime(true) - APP_START_MICROTIME, 3) . " sec",
        ]);
    }
}