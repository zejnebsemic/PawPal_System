<?php
require_once __DIR__ . 'BaseService.php';
require_once __DIR__ . '/../dao/AdminDAO.php';
class AdminService extends BaseService {
    public function __construct() {
        parent::__construct(new AdminDao());
    }

    public function create_admin($admin) {
        return $this->dao->create_admin($admin);
    }

    public function get_admin_by_id($admin_id) {
        return $this->dao->get_admin_by_id($admin_id);
    }

    public function get_all_admins() {
        return $this->dao->get_all_admins();
    }

    public function get_admin_with_user_info($admin_id) {
        return $this->dao->get_admin_with_user_info($admin_id);
    }

    public function update_admin($admin_id, $admin) {
        return $this->dao->update_admin($admin_id, $admin);
    }

    public function delete_admin($admin_id) {
        return $this->dao->delete_admin($admin_id);
    }
}
?>
