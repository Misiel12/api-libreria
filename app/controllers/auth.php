<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 06/12/2016
 * Time: 9:35
 */
namespace LIBRERIA\API;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;



class auth
{



    public function Login($request, $response, $args)
{

    // se obtiene el body
    $json = $request->getBody();

    //se convierte el body a un objeto
    $body = json_decode($json);


    try {
        $query = "SELECT id, nombre, correo FROM users WHERE correo = '".$body->correo ."' and pass = '".$body->pass."' ";

        //se obtiene la conexion a traves del metodo getconnection
        $conn = Conecction::getConnection();
        // se toma el dato recuperado
        $dat = $conn->query($query)->fetch();

        if (!$dat)
            throw  new Exception("No se pudo recuperar info");


        $token = array(


                "nombre" => $dat->nombre ,
                "correo" => $dat->correo ,
                'id' => $dat->id

        );
        $sign = new Token();

        $jwt = $sign->singing($token);



        $data["status"] = "ok";
        $data["user"] = $dat;
        $data["token"] = $jwt;

       // $data["token"]=$sign->verify('eydadss.assdasd.asdasd');



    } catch (Exception $e) {

        return $response->withJSON(['error' => $e->getMessage()], 500);
    }
    return $response->withJSON($data, 200);
}}