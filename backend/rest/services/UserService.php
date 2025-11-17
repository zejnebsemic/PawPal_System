<?php
require_once __DIR__ . '/BaseService.php';
require_once(__DIR__ . '/../dao/UserDAO.php');

class UserService extends BaseService {

    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function create_user($user) {
        try {
            $result = $this->dao->create_user($user);
            if ($result) {
                $user_id = $this->dao->getConnection()->lastInsertId();
                $user['user_id'] = $user_id;
                return ['success' => true, 'data' => $user];
            } else {
                return ['success' => false, 'error' => 'Unable to create user.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_user_by_id($user_id) {
        try {
            $user = $this->dao->get_user_by_id($user_id);
            if ($user) {
                return ['success' => true, 'data' => $user];
            } else {
                return ['success' => false, 'error' => 'User not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_all_users() {
        try {
            $users = $this->dao->get_all_users();
            return ['success' => true, 'data' => $users ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_user_by_email($email) {
        try {
            $user = $this->dao->get_user_by_email($email);
            if ($user) {
                return ['success' => true, 'data' => $user];
            } else {
                return ['success' => false, 'error' => 'User not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function update_user($user_id, $user) {
        try {
            $success = $this->dao->update_user($user_id, $user);
            if ($success) {
                return ['success' => true, 'data' => $this->dao->get_user_by_id($user_id)];
            } else {
                return ['success' => false, 'error' => 'Unable to update user.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function delete_user($user_id) {
        try {
            $success = $this->dao->delete_user($user_id);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Unable to delete user.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }
}
?>
