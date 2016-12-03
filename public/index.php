<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 29/11/2016
 * Time: 10:12
 */


require  __DIR__."../../vendor/autoload.php";

require  __DIR__."../../controllers/Customers.php";

require __DIR__.'../../app/api/db_connect.php';
$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new Slim\App($config);


$app->get("/", function(){
    return "hola mundo";
});

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:63342')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


require_once("../app/api/libros.php");
require_once("../app/api/generos.php");



$app->get("/customers", "Demo\Customers:getAll");
$app->get('/hola/{name}',"Demo\Customers:hola");




$app->run();