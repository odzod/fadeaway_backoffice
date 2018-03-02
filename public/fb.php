<?php
/**
 * Created by PhpStorm.
 * User: ptrn4883
 * Date: 02/03/2018
 * Time: 10:18
 */

require_once __DIR__ . '/../vendor/Facebook/autoload.php';

$token_page = "EAAcQrm4dKVMBAFqFoWTAv2uEmuZArGcFQRrRJvdrnEaZCZCbSrKEpjJxATr1rZC4tL3GAPSeu2MmUmTzkEPYIGOLNEdkOfLzmWz42ns7t5Nwb7PJytDAIm15tZACKGhnKkpM9EawpJnZCKxfY5DBpntcdff3biAeFdOpPDYDWLKA1n9CeBXhZCW14UmyYudxOIWCad3tx3fGQZDZD";
$token_pubish = "EAAcQrm4dKVMBAPO9KuE19tE7g7LBUaGpe5OEMc5qKRR4279UKxuHBwo9uuzL2hi5zZCUVlOMt8ZAzqzh7boGe5k6xqp4zP3qdKsEWRPe8uVZBFueaQZAXHnesZCNhRButGWa47kPPZA6eZAj4QWr1BwOWQd6ZBCPM4f7vZC0yJlRj9kfJfqjafa74d9VsSZAQv8meAUFZCNdLkIrAZDZD";
$token_long = "EAAcQrm4dKVMBAEZAAszzChZBUkK6gLYhTZB7B5l7FAV4hfCPIY9HF1D3jgwcD5hZCsKCfdsl2ZA96E4fd45TFbfPU7EVoaPZALJNQZAZBjgG602EgDgL81CXumJfqBFC5Qun7vCoVrZCX64CdzZCAt6cfKO6kw7DiheBenitVhEmt7rAZDZD";

$fb = new \Facebook\Facebook([
    'app_id' => '1988666194733395',
    'app_secret' => '8780450b687f8dfa978d15935d69c2ce',
    'default_graph_version' => 'v2.12',
    'default_access_token' => $token_long, // optional
]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

try {
    // Get the \Facebook\GraphNodes\GraphUser object for the current user.
    // If you provided a 'default_access_token', the '{access-token}' is optional.
    $response = $fb->post('/feed?message="test', $token_long);
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$me = $response->getGraphUser();
echo 'Logged in as ' . $me->getName();


