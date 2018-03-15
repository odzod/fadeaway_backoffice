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


function element_to_obj($element)
{
    echo $element->tagName, "\n";
    $obj = array("tag" => $element->tagName);
    foreach ($element->attributes as $attribute) {
        $obj[$attribute->name] = $attribute->value;
    }
    foreach ($element->childNodes as $subElement) {
        if ($subElement->nodeType == XML_TEXT_NODE) {
            $obj["html"] = $subElement->wholeText;
        } elseif ($subElement->nodeType == XML_CDATA_SECTION_NODE) {
            $obj["html"] = $subElement->data;
        } else {
            $obj["children"][] = element_to_obj($subElement);
        }
    }
    return $obj;
}

function html_to_obj($html)
{
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    return element_to_obj($dom->documentElement);
}

function redesign_image($img, $name)
{

//    $img = "https://scontent-cdt1-1.xx.fbcdn.net/v/t1.0-9/28660939_210820776162874_6194882502444195643_n.jpg";
    $chemin = $img; // Adresse complete
    $x_c = 750; // Taille de l'image
    $y_c = 500;
    $qualite = 80; // Qualite de l'image (0=pourrit/100=super)
    $color = "000000"; // Couleur de fond

    $size = getimagesize($chemin);

    if ($size[0] >= $x_c AND $size[1] >= $y_c) {
        if (($size[0] / $x_c) > ($size[1] / $y_c)) {
            $x_t = $x_c;
            $y_t = floor(($size[1] * $x_c) / $size[0]);
            $x_p = 0;
            $y_p = ($y_c / 2) - ($y_t / 2);
        } else {
            $x_t = floor(($size[0] * $y_c) / $size[1]);
            $y_t = $y_c;
            $x_p = ($x_c / 2) - ($x_t / 2);
            $y_p = 0;
        }
    } else {
        $x_t = $size[0];
        $y_t = $size[1];
        $x_p = ($x_c / 2) - ($x_t / 2);
        $y_p = ($y_c / 2) - ($y_t / 2);
    }

    $extension = strrchr($chemin, '.');
    $extension = strtolower(substr($extension, 1));

    if ($extension == 'jpg' OR $extension == 'jpeg') {
        $image_new = imagecreatefromjpeg($chemin);
    } elseif ($extension == 'gif') {
        $image_new = imagecreatefromgif($chemin);
    } elseif ($extension == 'png') {
        $image_new = imagecreatefrompng($chemin);
    } elseif ($extension == 'bmp') {
        $image_new = imagecreatefromwbmp($chemin);
    } else {
        echo "Erreur !";
        exit;
    }

//    Header("Content-type: image/jpeg");

    $image = imagecreatetruecolor($x_c, $y_c);
    $color = imagecolorallocate($image, hexdec($color[0] . $color[1]), hexdec($color[2] . $color[3]), hexdec($color[4] . $color[5]));
    imagefilledrectangle($image, 0, 0, $x_c, $y_c);
    imagecopyresampled($image, $image_new, $x_p, $y_p, 0, 0, $x_t, $y_t, $size[0], $size[1]);
    imagejpeg($image, __DIR__ . '/../public/images/' . $name . '.jpeg', $qualite);

    return true;

}


