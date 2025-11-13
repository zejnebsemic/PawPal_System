<?php
require __DIR__ . '/../vendor/autoload.php';
use OpenApi\Generator;

require 'rest/services/AdminService.php';
require 'rest/services/UserService.php';
require 'rest/services/PetService.php';
require 'rest/services/ShelterService.php';
require 'rest/services/ReviewService.php';
require 'rest/services/AdoptionRequestService.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('adminService', 'AdminService');
Flight::register('userService', 'UserService');
Flight::register('petService', 'PetService');
Flight::register('shelterService', 'ShelterService');
Flight::register('reviewService', 'ReviewService');
Flight::register('adoptionRequestService', 'AdoptionRequestService');

require_once __DIR__ . '/rest/routes/AdminRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/PetRoutes.php';
require_once __DIR__ . '/rest/routes/ShelterRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/AdoptionRequestRoutes.php';

Flight::route('/test', function() {
    echo "FlightPHP radi";
});


Flight::route('GET /swagger.json', function() {
    header('Content-Type: application/json');
    $openapi = Generator::scan([__DIR__ . '/../rest/routes']);
    echo $openapi->toJson();
});

Flight::start();
