<?php

namespace Niall\Controllers;

use Niall\Abstracts\Controller;
use Niall\Mind\Niall as NiallMind;
use Slim\Http\Request;
use Slim\Http\Response;
use Niall\Mind\Models;

class ExportController extends Controller{

    public function doExport(Request $request, Response $response, $args){
        return $response->withJson([
            "languages" => Models\NiallLanguage::search()->exec(),
            "words" => Models\NiallWord::search()->exec(),
            "word_langauge" => Models\NiallWordLanguage::search()->exec(),
            'word_relationship' => Models\NiallWordRelationship::search()->exec(),
            'response_time' => number_format(microtime(true) - APP_START_MICROTIME, 3) . " sec",
        ]);
    }
}