<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/UserDAO.php');

class UserService extends BaseService {

    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function create_user($user) {
        return $this->dao->create_user($user);
    }

    public function get_user_by_id($user_id) {
        return $this->dao->get_user_by_id($user_id);
    }

    public function get_all_users() {
        return $this->dao->get_all_users();
    }

    public function get_user_by_email($email) {
        return $this->dao->get_user_by_email($email);
    }

    public function update_user($user_id, $user) {
        return $this->dao->update_user($user_id, $user);
    }

    public function delete_user($user_id) {
        return $this->dao->delete_user($user_id);
    }
}
?>
