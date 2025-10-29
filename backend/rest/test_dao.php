<?php
require_once 'dao/AdminDao.php';
require_once 'dao/AdoptionRequestDao.php';
require_once 'dao/PetDao.php';
require_once 'dao/ReviewDao.php';
require_once 'dao/ShelterDao.php';
require_once 'dao/UserDao.php';

echo "<pre>";


$userDao = new UserDao();
//$userDao->add(['full_name' => 'John Doe', 'email' => 'john@example.com', 'password' => '12345']);
$user = $userDao->get_user_by_email('john@example.com');
echo "User by email:\n";
print_r($user);
$users = $userDao->get_all();
echo "\nAll users:\n";
print_r($users);


$adminDao = new AdminDao();
//$adminDao->add(['user_id' => 1]);
$admin = $adminDao->get_admin_with_user_info(1);
echo "\nAdmin with user info:\n";
print_r($admin);


$shelterDao = new ShelterDao();
//$shelterDao->add(['name' => 'Happy Tails Shelter', 'location' => 'Sarajevo', 'admin_id' => 1]);
$shelter = $shelterDao->get_shelter_by_name('Happy Tails Shelter');
echo "\nShelter by name:\n";
print_r($shelter);
$shelters = $shelterDao->get_shelters_with_admins();
echo "\nAll shelters with admins:\n";
print_r($shelters);


$petDao = new PetDao();
//$petDao->add(['name' => 'Bella', 'type' => 'Dog', 'shelter_id' => 1, 'availability' => 'available']);
$availablePets = $petDao->get_available_pets();
echo "\nAvailable pets:\n";
print_r($availablePets);
$petsByShelter = $petDao->get_pets_by_shelter(1);
echo "\nPets by shelter (ID 1):\n";
print_r($petsByShelter);
$petDetails = $petDao->get_pet_details(1);
echo "\nPet details (ID 1):\n";
print_r($petDetails);


$adoptionRequestDao = new AdoptionRequestDao();
//$adoptionRequestDao->add(['user_id' => 1, 'pet_id' => 1, 'status' => 'pending']);
$userRequests = $adoptionRequestDao->get_requests_by_user(1);
echo "\nAdoption requests by user (ID 1):\n";
print_r($userRequests);
$pendingRequests = $adoptionRequestDao->get_pending_requests();
echo "\nPending adoption requests:\n";
print_r($pendingRequests);


$reviewDao = new ReviewDao();
//$reviewDao->add(['user_id' => 1, 'shelter_id' => 1, 'rating' => 5, 'comment' => 'Excellent shelter!']);
$reviews = $reviewDao->get_reviews_by_shelter(1);
echo "\nReviews for shelter (ID 1):\n";
print_r($reviews);
$averageRating = $reviewDao->get_average_rating(1);
echo "\nAverage rating for shelter (ID 1):\n";
print_r($averageRating);

echo "</pre>";
?>
