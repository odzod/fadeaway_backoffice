<?php

use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: gdumeou
 * Date: 19/02/2018
 * Time: 23:07
 */
function response_notConnect(){
    $response = new Response(
        '',
        Response::HTTP_UNAUTHORIZED,
        array(
            'Access-Control-Allow-Origin'=>'*'
        )
    );
    $response->send();
    die;
}

//STRUCTURE RESPONSE OK
function response_ok($result){
    $response = new Response(
        $result,
        Response::HTTP_OK,
        array(
            'Access-Control-Allow-Origin'=>'*'
        )
    );
    $response->send();
    die;
}

function response_ko(){
    $response = new Response(
        '',
        Response::HTTP_FORBIDDEN,
        array(
            'Access-Control-Allow-Origin'=>'*'
        )
    );
    $response->send();
    die;
}

function getDb(){
    include_once(__DIR__."/../include/config.include.php");
    $dsn = sprintf( 'mysql:dbname=%s;host=%s', mysql_db, mysql_host);
    $db = new PDO($dsn,mysql_user,mysql_password);
    return $db;
}
