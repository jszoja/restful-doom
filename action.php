<?php

require "vendor/autoload.php";

use GuzzleHttp\Client;

 $port = 6666;
 $client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'http://localhost:'.$port,
    // You can set any number of default request options.
    'timeout'  => 10.0,
]);

switch ($_GET['k']) {
    case 's':
        $action = 'start';
        break;
    case 'ArrowRight':
        $action = 'turn-right';
        break;
    case 'ArrowLeft':
        $action = 'turn-left';
        break;
    case 'Shift':
        $action = 'shoot';
        break;
    case 'ArrowUp':
        $action = 'forward';
        break;
    case 'ArrowDown':
        $action = 'backward';
        break; 
    case 'Enter':
        $action = 'use';
        break;
    default:
        $action = 'forward';
        break;
    }



if($action === 'start') {
    $client->patch('/api/world', ['json' => ['map' => 1]]);
    file_put_contents('last-run.txt', '');    
} else {
    $client->post('/api/player/actions', ['json' => ['type' => $action]]);
    file_put_contents('last-run.txt', $action.' '.$_GET['t']."\n", FILE_APPEND);    
}

