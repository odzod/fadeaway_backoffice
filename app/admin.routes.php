<?php

include_once(__DIR__ . "/../include/functions.include.php");

$app->get('/sql/dbupdate', function () {

    if ($_REQUEST['token'] == 'ptrn4883') {
        require_once(__DIR__ . "/../sql/dbupdate.php");
        $res = update_db();
        $res = json_encode(array("success" => true, "data" => json_encode($res)));
        if (!$res) {
            response_ko();
        }
        response_ok($res);
    } else {
        response_ko();
    }

});

$app->get('admin/news/list', function () {

    redesign_image("http://api.fadeaway.fr/images/28660939_210820776162874_6194882502444195643_n.jpg","kawhi");
    $res = json_encode(array("success" => true));
    response_ok($res);

});