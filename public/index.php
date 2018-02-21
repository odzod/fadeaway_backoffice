<?php
session_start();

error_reporting(E_ERROR);
ini_set('display_errors', TRUE); // Error display
ini_set('log_errors', TRUE); // Error logging
ini_set('error_log', __DIR__."/../errors.log"); // Logging file
ini_set('log_errors_max_len', 1024); // Logging file size

use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Response;

header('Access-Control-Request-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,Accept-Encoding,Accept-Language');
header('Access-Control-Allow-Methods:  GET,PUT,POST,OPTIONS,DELETE,PATCH');
header('Content-Type: application/json');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../include/config.include.php';

$app = new Silex\Application();

$app->register(new SessionServiceProvider());
$app->register(new JDesrosiers\Silex\Provider\CorsServiceProvider(), []);

include_once(__DIR__ . '/../app/routes.php');

$app->run();