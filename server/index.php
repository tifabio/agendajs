<?php

require 'DB.php';

require 'Log/logentries.php';

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array('debug' => false));

$app->contentType("application/json");

$app->error(function ( Exception $e = null) use ($app) {
         echo '{"error":{"text":"'. $e->getMessage() .'"}}';
        });

function formatJson($obj)
{
    echo json_encode($obj);
}

// includes
include("agenda.php");

$app->run();

$log->Debug($app->request()->getMethod() . ' ' . $app->request()->getPath());