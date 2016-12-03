<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 29/11/2016
 * Time: 11:17
 */

class Conecction{

function getConnection()
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "demo";
  // return new mysqli($host, $user, $pass, $db_name);
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        //$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }catch (PDOException $e){
         throw  new Exception($e->getMessage(), $e->getCode());
    }

    return $conn;
}}