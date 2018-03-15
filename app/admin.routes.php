<?php

use Symfony\Component\HttpFoundation\Request;

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

    $model = new News(getDb());
    $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 0;
    $res = $model->getAdminNewsList($page);
    $res = json_encode(
        array(
          "total_count" => count($res)
        , "items" => $res
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

$app->get('admin/news/add', function(){
   $model = new News(getDb());
   $id = $model->addNews();
   $res = json_encode(array(
        "success"=>true,
       "id"=>$id
   ));
   response_ok($res);
});

$app->post('admin/news/update', function (Request $request) use ($app){
    print_r($_FILES['uploadFile']);
});