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
