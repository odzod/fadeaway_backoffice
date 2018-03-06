<?php

use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: gdumeou
 * Date: 19/02/2018
 * Time: 23:07
 */
function response_notConnect()
{
    $response = new Response(
        '',
        Response::HTTP_UNAUTHORIZED,
        array(
            'Access-Control-Allow-Origin' => '*'
        )
    );
    $response->send();
    die;
}

//STRUCTURE RESPONSE OK
function response_ok($result)
{
    $response = new Response(
        $result,
        Response::HTTP_OK,
        array(
            'Access-Control-Allow-Origin' => '*'
        )
    );
    $response->send();
    die;
}

//STRUCTURE RESPONSE KO
function response_ko()
{
    $response = new Response(
        '',
        Response::HTTP_FORBIDDEN,
        array(
            'Access-Control-Allow-Origin' => '*'
        )
    );
    $response->send();
    die;
}

// CONNEXION A LA BDD
function getDb()
{
    include_once(__DIR__ . "/../include/config.include.php");
    try {
        $dsn = sprintf('mysql:dbname=%s;host=%s', mysql_db, mysql_host);
        $db = new PDO($dsn, mysql_user, mysql_password);
    } catch (Exception $e) {
        return false;
    }
    return $db;

}


function element_to_obj($element) {
    echo $element->tagName, "\n";
    $obj = array( "tag" => $element->tagName );
    foreach ($element->attributes as $attribute) {
        $obj[$attribute->name] = $attribute->value;
    }
    foreach ($element->childNodes as $subElement) {
        if ($subElement->nodeType == XML_TEXT_NODE) {
            $obj["html"] = $subElement->wholeText;
        }
        elseif ($subElement->nodeType == XML_CDATA_SECTION_NODE) {
            $obj["html"] = $subElement->data;
        }
        else {
            $obj["children"][] = element_to_obj($subElement);
        }
    }
    return $obj;
}

function html_to_obj($html) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    return element_to_obj($dom->documentElement);
}
