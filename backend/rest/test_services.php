<?php
require_once 'services/UserService.php';
require_once 'services/AdminService.php';
require_once 'services/ShelterService.php';
require_once 'services/PetService.php';
require_once 'services/ReviewService.php';
require_once 'services/AdoptionRequestService.php';

$userService = new UserService();
$adminService = new AdminService();
$shelterService = new ShelterService();
$petService = new PetService();
$reviewService = new ReviewService();
$requestService = new AdoptionRequestService();

//$userService->create_user([
//    'full_name' => 'Enver Sutic',
//    'email' => 'enver@gmail.com',
//    'password_hash' => password_hash('123', PASSWORD_DEFAULT),
//    'role' => 'user'
//]);

$user = $userService->get_user_by_email('enver@gmail.com');
echo "<pre>User:\n";
print_r($user);
echo "</pre>";

//$adminService->create_admin(['user_id' => 1]);
$admins = $adminService->get_all_admins();
echo "<pre>Admins:\n";
print_r($admins);
echo "</pre>";

//$shelterService->create_shelter(['name' => 'Happy Paws', 'address' => 'Sarajevo', 'phone' => '061234567', 'admin_id' => 1]);
$shelters = $shelterService->get_all_shelters();
echo "<pre>Shelters:\n";
print_r($shelters);
echo "</pre>";

//$petService->create_pet(['name' => 'Buddy', 'type' => 'dog', 'shelter_id' => 1]);
$pets = $petService->get_all_pets();
echo "<pre>Pets:\n";
print_r($pets);
echo "</pre>";

//$reviewService->create_review(['shelter_id' => 1, 'user_id' => 2, 'rating' => 5, 'comment' => 'Excellent shelter!']);
$reviews = $reviewService->get_all_reviews();
echo "<pre>Reviews:\n";
print_r($reviews);
echo "</pre>";

//$requestService->create_request(['pet_id' => 1, 'user_id' => 2, 'admin_id' => 1, 'status' => 'pending']);
$requests = $requestService->get_all_requests();
echo "<pre>Adoption Requests:\n";
print_r($requests);
echo "</pre>";
?>
