<?php

include_once(__DIR__ . "/../include/functions.include.php");

$app->get('/sql/dbupdate', function () {

    if ($_REQUEST['token'] == 'ptrn4883') {
        require_once(__DIR__ . "/../sql/dbupdate.php");
        $res = update_db();
        $res = json_encode(array("success" => true, "data" => json_encode($res)));
        response_ok($res);
    } else {
        response_ko();
    }

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