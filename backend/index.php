<?php

require 'vendor/autoload.php';
require 'rest/services/AuthService.php';
require 'rest/services/AdminService.php';
require 'rest/services/UserService.php';
require 'rest/services/PetService.php';
require 'rest/services/ShelterService.php';
require 'rest/services/ReviewService.php';
require 'rest/services/AdoptionRequestService.php';
require "middleware/AuthMiddleware.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('auth_service', "AuthService");
Flight::register('adminService', 'AdminService');
Flight::register('userService', 'UserService');
Flight::register('petService', 'PetService');
Flight::register('shelterService', 'ShelterService');
Flight::register('reviewService', 'ReviewService');
Flight::register('adoptionRequestService', 'AdoptionRequestService');
Flight::register('auth_middleware', "AuthMiddleware");


Flight::route('GET /', function() {
    Flight::json([
        'status' => 'ok',
        'message' => 'PawPal System API is running',
        'version' => '1.0'
    ]);
});

require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/AdminRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/PetRoutes.php';
require_once __DIR__ . '/rest/routes/ShelterRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/AdoptionRequestRoutes.php';

Flight::start();
