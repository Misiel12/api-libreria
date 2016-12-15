<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 05/12/2016
 * Time: 11:32
 */

$app->group('/api/libros', function () use ($app) {


    $app->get('', 'LIBRERIA\API\Libros::getAll');
    $app->post('', 'LIBRERIA\API\Libros::create');
    $app->get('/hola', 'LIBRERIA\API\Libros::hola');
    $app->get('/{id}', 'LIBRERIA\API\Libros::getOne');
    $app->put('/{id}', 'LIBRERIA\API\Libros::modify');
    $app->delete('/{id}', 'LIBRERIA\API\Libros::delete');


})->add( new \LIBRERIA\MIDDLEWARE\verifyToken());

$app->post('/login', 'LIBRERIA\API\auth::Login');
$app->post('/registro', 'LIBRERIA\API\auth::Login');