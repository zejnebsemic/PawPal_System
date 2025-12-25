<?php
require_once __DIR__ . '/BaseDao.php';


class AuthDao extends BaseDao {
   protected $table_name;


   public function __construct() {
       $this->table_name = "users";
       parent::__construct($this->table_name, "user_id");
   }


   public function get_user_by_email($email) {
       $query = "SELECT user_id, email, password_hash, full_name, phone_number, date_of_birth, address, city, state, zip_code, about_me, role, created_at FROM " . $this->table_name . " WHERE email = :email";
       $user = $this->query_unique($query, ['email' => $email]);
       
       
       if ($user) {
           error_log("AuthDao::get_user_by_email - User found. Has password_hash: " . (isset($user['password_hash']) ? 'YES' : 'NO'));
           if (isset($user['password_hash'])) {
               error_log("AuthDao::get_user_by_email - password_hash length: " . strlen($user['password_hash']));
           }
       }
       
       return $user;
   }
}
