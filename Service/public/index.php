<?php
require_once("../bootstrap.php");

\Niall\Niall::Instance()
    ->loadAllRoutes()
    ->getApp()
        ->run();