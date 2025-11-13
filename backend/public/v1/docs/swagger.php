<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../../../vendor/autoload.php';

if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    define('BASE_URL', 'http://localhost/PawPal_System/backend');
} else {
    define('BASE_URL', 'https://add-production-server-after-deployment/backend/');
}

$openapi = \OpenApi\Generator::scan([
    '/Users/zejnebsemic/PawPal_System/backend/public/v1/docs/doc_setup.php',
    '/Users/zejnebsemic/PawPal_System/backend/rest/routes'
]);
header('Content-Type: application/json');
echo $openapi->toJson();
?>
