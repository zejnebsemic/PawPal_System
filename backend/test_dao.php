<?php
require_once __DIR__ . '/rest/dao/UserDAO.class.php';
require_once __DIR__ . '/rest/dao/AdminDAO.class.php';
require_once __DIR__ . '/rest/dao/ShelterDAO.class.php';
require_once __DIR__ . '/rest/dao/PetDAO.class.php';
require_once __DIR__ . '/rest/dao/AdoptionRequestDAO.class.php';
require_once __DIR__ . '/rest/dao/ReviewDAO.class.php';

echo "<pre>";

$userDao = new UserDao();
//$userDao->insert(['full_name' => 'John Doe', 'email' => 'zejneb@example.com', 'password_hash' => '12345']);
$user = $userDao->get_user_by_email('john@example.com');
print_r($user);
$users = $userDao->getAll();
print_r($users);

$adminDao = new AdminDao();
//$adminDao->insert(['user_id' => 1]);
$admin = $adminDao->get_admin_with_user_info(1);
print_r($admin);

$shelterDao = new ShelterDao();
//$shelterDao->insert(['admin_id' => 1, 'name' => 'Happy Tails Shelter', 'address' => 'Sarajevo', 'working_days' => 'Mon-Fri', 'working_hours' => '9-17']);
$shelter = $shelterDao->get_shelter_by_name('Happy Tails Shelter');
print_r($shelter);
$shelters = $shelterDao->get_shelters_with_admins();
print_r($shelters);

$petDao = new PetDao();
//$petDao->insert(['shelter_id' => 1, 'name' => 'Bella', 'type' => 'dog', 'age' => 2, 'availability' => 'available']);
$availablePets = $petDao->get_available_pets();
print_r($availablePets);
$petsByShelter = $petDao->get_pets_by_shelter(1);
print_r($petsByShelter);
$petDetails = $petDao->get_pet_details(1);
print_r($petDetails);

$adoptionRequestDao = new AdoptionRequestDao();
//$adoptionRequestDao->insert(['user_id' => 1, 'pet_id' => 1, 'admin_id' => 1, 'status' => 'pending']);
$userRequests = $adoptionRequestDao->get_requests_by_user(1);
print_r($userRequests);
$pendingRequests = $adoptionRequestDao->get_pending_requests();
print_r($pendingRequests);

$reviewDao = new ReviewDao();
//$reviewDao->insert(['user_id' => 1, 'shelter_id' => 1, 'rating' => 5, 'comment' => 'The best shelter!']);
$reviews = $reviewDao->get_reviews_by_shelter(1);
print_r($reviews);
$averageRating = $reviewDao->get_average_rating(1);
print_r($averageRating);

echo "</pre>";
?>
