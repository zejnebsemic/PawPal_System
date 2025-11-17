<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AdminDAO.php';

class AdminService extends BaseService {

    public function __construct() {
        parent::__construct(new AdminDao());
    }

    public function create_admin($admin) {
        try {
            $result = $this->dao->create_admin($admin);
            if ($result) {
                $admin_id = $this->dao->getConnection()->lastInsertId();
                $admin['admin_id'] = $admin_id;
                return ['success' => true, 'data' => $admin];
            } else {
                return ['success' => false, 'error' => 'Unable to create admin.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_admin_by_id($admin_id) {
        try {
            $admin = $this->dao->get_admin_by_id($admin_id);
            if ($admin) {
                return ['success' => true, 'data' => $admin];
            } else {
                return ['success' => false, 'error' => 'Admin not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_all_admins() {
        try {
            $admins = $this->dao->get_all_admins();
            return ['success' => true, 'data' => $admins ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_admin_with_user_info($admin_id) {
        try {
            $admin = $this->dao->get_admin_with_user_info($admin_id);
            if ($admin) {
                return ['success' => true, 'data' => $admin];
            } else {
                return ['success' => false, 'error' => 'Admin with user info not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function update_admin($admin_id, $admin) {
        try {
            $success = $this->dao->update_admin($admin_id, $admin);
            if ($success) {
                return ['success' => true, 'data' => $this->dao->get_admin_by_id($admin_id)];
            } else {
                return ['success' => false, 'error' => 'Unable to update admin.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function delete_admin($admin_id) {
        try {
            $success = $this->dao->delete_admin($admin_id);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Unable to delete admin.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }
}
?>
