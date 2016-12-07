<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 06/12/2016
 * Time: 15:34
 */

namespace LIBRERIA\MIDDLEWARE;


class verifyToken
{
    public function __invoke($req, $res, $next)
    {
        //antes de entrar a los metodos
        if($req->hasHeader('X-Token')) {
            $token = $req->getHeader('X-Token')[0];


            try {
                $jwt = new \LIBRERIA\API\Token();
                $data = $jwt->verify($token);


                if (!isset($data))
                    return $res->withJSON(['ERROR' => 'UNAUTHORIZED'], 401);

            } catch (Exception $e) {
                return $res->withJSON(['error' => 'Sin autorizacion'], 401);
            }
        }else{
            return $res->withJSON(['msg'=> "cabecera X-token desconocida"], 401);
        }


        $res = $next($req, $res);


        return $res;
    }

}