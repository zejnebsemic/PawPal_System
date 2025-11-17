<?php
require_once __DIR__ . "/BaseDao.php";

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users", "user_id");
    }

    public function create_user($user) {
        return $this->insert($user);
    }

    public function get_user_by_id($user_id) {
        return $this->getById($user_id);
    }

    public function get_all_users() {
        return $this->getAll();
    }

    public function get_user_by_email($email) {
        return $this->query_unique("SELECT * FROM users WHERE email = :email", ["email" => $email]);
    }

    public function update_user($user_id, $user) {
        return $this->update($user_id, $user);
    }

    public function delete_user($user_id) {
        return $this->delete($user_id);
    }
    public function getConnection() {
    return $this->connection;}
}
?>
