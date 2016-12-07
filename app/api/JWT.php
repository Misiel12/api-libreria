<?php

/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 06/12/2016
 * Time: 10:58
 */
namespace LIBRERIA\API;

class Token
{

    public $key = "2JhXO87zJbIzBiDIb4It2P7HrJlLK9Ev";

    public function singing($data)
    {

        $token = null;

        try {

            $payload = [
                "iss" => 'ali@gmail.com',
                "iat"=>time(),
                "exp" => time() + ( 20),
                "data" => $data
            ];

            $token = \Firebase\JWT\JWT::encode($payload, $this->key);


        } catch (Exception $e) {

            throw  new Exception(" Error en JWT " . $e->getMessage());
        }

        return $token;

    }

    public function verify($token)
    {
        $payload = null;
        try {

            $payload = \Firebase\JWT\JWT::decode($token, $this->key,array('HS256'));

        } catch (Exception $e) {
            throw  new Exception(" Error en JWT " . $e->getMessage());
        }

        return $payload;
    }

}