<?php

include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/config.php';
include __DIR__ . '/ShortUrl.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

function getPDOInstance(){
    return new PDO(DB_DRIVER. ":host=" .DB_HOST. ";dbname=" .DB_NAME,DB_USER, DB_PASSWORD);
}

function processInput(){
    return urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

$router = new RouteCollector();

$router->get('/', function(){
    print file_get_contents('main.html');
});

$router->post('/', function(){
    if (isset($_POST['clientLink'])) {
        $clientLink = $_POST['clientLink'];
        if ($link = ShortUrl::find('long_url', $clientLink)) {
            print $link->short_code;
        }
        elseif ($link = ShortUrl::create($clientLink)) {
            print $link->short_code;
        }
    }
});

$router->get('/{hash:[a-zA-Z0-9]+}', function($hash){
    if ($link = ShortUrl::find('short_code', $hash)) {
        header('Location: ' . $link->long_url, true, 301);
        die();
    }
    else {
        print ERROR_REDIRECT_TEXT;
    }
});


$dispatcher =  new Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], processInput());