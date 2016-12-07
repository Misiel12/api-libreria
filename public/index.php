<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 29/11/2016
 * Time: 10:12
 */


require __DIR__ . "../../vendor/autoload.php";
//require __DIR__ . '../../app/loader.php';
require __DIR__ . '../../app/api/JWT.php';
require __DIR__ . '../../app/api/db_connect.php';
require __DIR__ . '../../app/controllers/Libros.php';
require __DIR__ . '../../app/controllers/auth.php';
require __DIR__ . '../../app/middleware/verifyToken.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new Slim\App($config);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:63342')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


require __DIR__ . '../../app/rutas.php';

$app->run();