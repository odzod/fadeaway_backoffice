<?php


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