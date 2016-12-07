<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 06/12/2016
 * Time: 15:49
 */

$base = "../app/";

$folders = [
    'api',
   'middleware',
    'controllers'

];

foreach ($folders as $folder){
    foreach (glob($base."$folder/*.php") as $filename){
        require  $filename;
        echo $filename;
    }
}





