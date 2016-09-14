<?php
$app->group("/", function(){
    $this->get("", \Niall\Controllers\JabberController::class . ":showJabber")->setName("Homepage");
});
$app->group("/v1", function () {
    $this->get("", \Niall\Controllers\ApiListController::class . ':listAllRoutes')   ->setName("List all routes");
    $this->group("/ping", function () {
        $this->any("", \Niall\Controllers\PingController::class . ':doPing')->setName("Ping!");
    });
    $this->map(["GET", "POST"], "/speak", \Niall\Controllers\ResponseController::class . ':doSpeak')->setName("Talk to the Guru and get a response.");
    $this->map(["POST"], "/listen", \Niall\Controllers\ResponseController::class . ':doListen')->setName("Make the Guru Listen.");
    $this->get("/export", \Niall\Controllers\ExportController::class . ":doExport")->setName("Do export as a hulking brick of JSON.");
    $this->get("/stats", \Niall\Controllers\ExportController::class . ":doStats")->setName("Show Stats");
});
