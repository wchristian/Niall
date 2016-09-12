<?php
namespace Niall\Controllers;

use Monolog\Logger;
use Niall\Niall as App;
use Segura\AppCore\Abstracts\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class PingController extends Controller
{
    public function __construct()
    {
        $this->setApiExplorerEnabled(false);
    }

    public function doPing(Request $request, Response $response, array $args)
    {
        App::Log(Logger::DEBUG, "Pinged");
        return $this->jsonResponse(
            [
                'Status' => 'Okay',
            ],
            $request,
            $response
        );
    }
}
