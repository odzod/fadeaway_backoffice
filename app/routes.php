<?php

use Symfony\Component\HttpFoundation\Request;

include_once(__DIR__ . "/../include/functions.include.php");
include_once(__DIR__ . "/../include/class.include.php");

// Les routes admins sont simplements séparés dans un fichier pour l'instant
include_once(__DIR__ . "/../app/admin.routes.php");


//ici les routes de l'api "normal"
$app->get('/news/last', function () {

    $model = new News(getDb());
    $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 0;
    $res = $model->getLastNews($page);
    $res = json_encode(
        array(
            "success" => true
        , "data" => $res
        )
    );
    if (!$res) {
        response_ok(json_encode(array(
            "success" => false
        , "msg" => "Erreur RES (News)getLastNews"
        )));
    }
    response_ok($res);

});

$app->get('/news/{idNews}', function ($idNews,Request $request) use ($app){
    $model = new News(getDb());
    $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 0;
    $res = $model->getNews($idNews);
    $res = json_encode(
        array(
            "success" => true
        , "data" => $res
        ),JSON_HEX_QUOT | JSON_HEX_TAG
    );
    if (!$res) {
        response_ok(json_encode(array(
            "success" => false
        , "msg" => "Erreur RES (News)getLastNews"
        )));
    }
    response_ok($res);
});

/*
$app->post('/tiers/connexion', function (Request $request) use ($app){
    $trackingLogin = $request->request->get("trackingLogin");
    $trackingPassword = $request->request->get("trackingPassword");
    if(empty($trackingLogin) or empty($trackingPassword))
        response_false();
    $trackingPassword = encode_password($trackingPassword);
    $result = curl_post("/tiers/connexion",array("trackingLogin"=>$trackingLogin,"trackingPassword"=>$trackingPassword));
    if(!$result) response_false();
    $res = json_decode(($result));
    $connexion = $res->connexion;
    if(!isset($connexion->id)) response_false();
    $app['session']->set('log_in',true);
    $today = date("Y-m-d H:i:s");
    $app['session']->set('exposedDate',date('Y-m-d H:i:s', strtotime($today . ' + 20 minutes')));
    $app['session']->set('userinfos',$connexion);
    $connexion->tokenAuthentification  = $app['session']->get('li_token');
    response_ok(json_encode($connexion));
});
	$app->get('/tiers/{idTiers}/commande/stats', function ($idTiers,Request $request) use ($app){
		if(!verif_connexion($app['session']->get('log_in')))
			response_notConnect();
		$_availableKeys = array("docNum","refClient","dateCreatMax","dateCreatMin","itemPerPage","page","sorts");
		$url_params = "?1=1";
		foreach($_REQUEST as $key => $val){
			if(in_array($key,$_availableKeys)){
				$url_params .= "&".$key."=".$val;
			}
		}
		// die("/tiers/".$app->escape($idTiers)."/commande".$url_params);
	    $result = curl_get("/tiers/".$app->escape($idTiers)."/commande/stats".$url_params);
	    if(!$result) response_false();
	    response_ok($result);
	});
*/