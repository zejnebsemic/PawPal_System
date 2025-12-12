<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'rest/services/AdminService.php';
require 'rest/services/UserService.php';
require 'rest/services/PetService.php';
require 'rest/services/ShelterService.php';
require 'rest/services/ReviewService.php';
require 'rest/services/AdoptionRequestService.php';
require 'rest/services/AuthService.php';
require 'rest/services/RestaurantService.php';

Flight::register('adminService', 'AdminService');
Flight::register('userService', 'UserService');
Flight::register('petService', 'PetService');
Flight::register('shelterService', 'ShelterService');
Flight::register('reviewService', 'ReviewService');
Flight::register('adoptionRequestService', 'AdoptionRequestService');
Flight::register('auth_service', 'AuthService');
Flight::register('restaurantService', 'RestaurantService');


Flight::route('/*', function() {
    $url = Flight::request()->url;

    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0
    ) {
        return TRUE;
    }

    try {
        $token = Flight::request()->getHeader("Authentication");
        if (!$token) {
            Flight::halt(401, "Missing authentication header");
        }

        $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

        Flight::set('user', $decoded_token->user);
        Flight::set('jwt_token', $token);

        return TRUE;
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

require_once __DIR__ . '/rest/routes/AdminRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/PetRoutes.php';
require_once __DIR__ . '/rest/routes/ShelterRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/AdoptionRequestRoutes.php';
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/RestaurantRoutes.php';

Flight::route('/test', function() {
    echo "FlightPHP radi";
});

Flight::start();
