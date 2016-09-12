<?php

namespace Niall\Controllers;

use Niall\Abstracts\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class JabberController extends Controller{
    protected $twig;

    public function __construct(
        Twig $twig
    ){
        $this->twig = $twig;
    }

    public function showJabber(Request $request, Response $response, $args)
    {
        return $this->twig->render(
            $response,
            "jabber.html.twig",
            []
        );
    }

}