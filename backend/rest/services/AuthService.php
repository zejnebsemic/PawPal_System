<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {
   private $auth_dao;
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
   }


   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }


   public function register($entity) {  
       try {
           
           if (!is_array($entity)) {
               error_log("Register: entity is not an array. Type: " . gettype($entity) . ", Value: " . print_r($entity, true));
               return ['success' => false, 'error' => 'Invalid request data format.'];
           }
           
           $email = isset($entity['email']) ? trim($entity['email']) : '';
           $password = isset($entity['password']) ? trim($entity['password']) : '';
           
           if (empty($email) || empty($password)) {
               error_log("Register: Missing required fields. Email: " . ($email ?: 'missing') . ", Password: " . ($password ? 'provided' : 'missing'));
               return ['success' => false, 'error' => 'Email and password are required.', 'error_type' => 'validation'];
           }
           
           
           $entity['email'] = $email;
           $entity['password'] = $password;

           
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               return ['success' => false, 'error' => 'Invalid email format.', 'error_type' => 'validation'];
           }

           
           if (strlen($password) < 8) {
               return ['success' => false, 'error' => 'Password must be at least 8 characters long.', 'error_type' => 'validation'];
           }
           if (strlen($password) > 20) {
               return ['success' => false, 'error' => 'Password cannot exceed 20 characters.', 'error_type' => 'validation'];
           }

           
           if (isset($entity['full_name']) && !empty(trim($entity['full_name']))) {
               if (strlen(trim($entity['full_name'])) < 2) {
                   return ['success' => false, 'error' => 'Full name must be at least 2 characters long.'];
               }
               if (strlen($entity['full_name']) > 100) {
                   return ['success' => false, 'error' => 'Full name cannot exceed 100 characters.'];
               }
           }

           
           if (isset($entity['phone_number']) && !empty(trim($entity['phone_number']))) {
               $phone = trim($entity['phone_number']);
               if (strlen($phone) < 10 || strlen($phone) > 20) {
                   return ['success' => false, 'error' => 'Phone number must be between 10 and 20 characters.'];
               }
              
               if (!preg_match('/^[0-9\s\-\(\)\+]+$/', $phone)) {
                   return ['success' => false, 'error' => 'Phone number contains invalid characters.'];
               }
           }

           
           if (isset($entity['address']) && !empty(trim($entity['address']))) {
               if (strlen(trim($entity['address'])) < 5) {
                   return ['success' => false, 'error' => 'Address must be at least 5 characters long.'];
               }
               if (strlen($entity['address']) > 200) {
                   return ['success' => false, 'error' => 'Address cannot exceed 200 characters.'];
               }
           }

           $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
           if($email_exists){
               return ['success' => false, 'error' => 'Email already registered.'];
           }

           
           $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
           unset($entity['password']); 

           $userId = parent::add($entity);
           
           if (!$userId) {
               return ['success' => false, 'error' => 'Failed to create user.'];
           }

          
           $created_user = $this->auth_dao->getById($userId);
           
           if (!$created_user) {
               return ['success' => false, 'error' => 'User created but could not be retrieved.'];
           }

           unset($created_user['password_hash']);

           return ['success' => true, 'data' => $created_user];             
       } catch (PDOException $e) {
           error_log("Database error in register: " . $e->getMessage());
           return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
       } catch (Exception $e) {
           error_log("Error in register: " . $e->getMessage());
           return ['success' => false, 'error' => 'An error occurred: ' . $e->getMessage()];
       }
   }


   public function login($entity) {  
       try {
           
           if (!is_array($entity)) {
               error_log("Login: entity is not an array. Type: " . gettype($entity) . ", Value: " . print_r($entity, true));
               return ['success' => false, 'error' => 'Invalid request data format.'];
           }
           
           error_log("AuthService::login - Entity received: " . print_r($entity, true));
           error_log("AuthService::login - Has email: " . (isset($entity['email']) ? 'YES (' . $entity['email'] . ')' : 'NO'));
           error_log("AuthService::login - Has password: " . (isset($entity['password']) ? 'YES (length: ' . strlen($entity['password']) . ')' : 'NO'));
           
           
           $email = isset($entity['email']) ? trim($entity['email']) : '';
           $password = isset($entity['password']) ? trim($entity['password']) : '';
           
           if (empty($email) || empty($password)) {
               error_log("Login: Missing required fields. Email: " . ($email ?: 'missing') . ", Password: " . ($password ? 'provided' : 'missing'));
               return ['success' => false, 'error' => 'Email and password are required.', 'error_type' => 'validation'];
           }

          
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               return ['success' => false, 'error' => 'Invalid email format.', 'error_type' => 'validation'];
           }
           
           
           $entity['email'] = $email;
           $entity['password'] = $password;

           $user = $this->auth_dao->get_user_by_email($entity['email']);
           if(!$user){
               return ['success' => false, 'error' => 'Invalid username or password.'];
           }

           
           error_log("AuthService::login - User retrieved. Keys: " . implode(', ', array_keys($user)));
           error_log("AuthService::login - Has password_hash: " . (isset($user['password_hash']) ? 'YES' : 'NO'));
           if (isset($user['password_hash'])) {
               error_log("AuthService::login - password_hash value: " . substr($user['password_hash'], 0, 20) . "...");
           }

           
           if (!isset($user['password_hash']) || empty($user['password_hash'])) {
               error_log("Login: User found but password_hash is missing for email: " . $entity['email']);
               error_log("Login: Available user keys: " . implode(', ', array_keys($user)));
               return ['success' => false, 'error' => 'Invalid username or password.'];
           }

           if (!password_verify($entity['password'], $user['password_hash'])) {
               error_log("Login: Password verification failed for email: " . $entity['email']);
               return ['success' => false, 'error' => 'Invalid username or password.'];
           }

           
           unset($user['password_hash']);
           unset($user['password']); 
          
           $jwt_payload = [
               'user' => $user,
               'iat' => time(),
               
               'exp' => time() + (60 * 60 * 24) 
           ];

           $token = JWT::encode(
               $jwt_payload,
               Config::JWT_SECRET(),
               'HS256'
           );

          
          return [
              'success' => true, 
              'data' => [
                  'user' => $user,
                  'token' => $token
              ]
          ];             
       } catch (PDOException $e) {
           error_log("Database error in login: " . $e->getMessage());
           return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
       } catch (Exception $e) {
           error_log("Error in login: " . $e->getMessage());
           return ['success' => false, 'error' => 'An error occurred: ' . $e->getMessage()];
       }
   }
}
