<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 29/11/2016
 * Time: 10:21
 */

namespace Demo;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

class Customers
{

    public function getAll(Request $req, Response $res){


        return $res->withJSON(['nombre'=>'ali']);
    }


  public  function hola (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hola, $name");

    return $response;
}





}