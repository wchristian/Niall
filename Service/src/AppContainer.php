<?php

$this->container[\Niall\Controllers\ResponseController::class] = function (\Slim\Container $c) {
    return new \Niall\Controllers\ResponseController(
        $c->get(\Niall\Mind\Niall::class)
    );
};

$this->container[\Niall\Mind\Niall::class] = function (\Slim\Container $c){
    return new \Niall\Mind\Niall();
};

$this->container[\Niall\Controllers\JabberController::class] = function (\Slim\Container $c){
    return new \Niall\Controllers\JabberController(
        $c->get("view")
    );
};
