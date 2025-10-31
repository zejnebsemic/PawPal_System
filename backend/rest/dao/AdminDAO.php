<?php
require_once __DIR__ . "/BaseDao.php";

class AdminDao extends BaseDao {
    public function __construct() {
        parent::__construct("admins", "admin_id");
    }

    public function create_admin($admin) {
        return $this->insert($admin);
    }

    public function get_admin_by_id($admin_id) {
        return $this->getById($admin_id);
    }

    public function get_all_admins() {
        return $this->getAll();
    }

    public function get_admin_with_user_info($admin_id) {
        return $this->query_unique("
            SELECT a.*, u.full_name, u.email 
            FROM admins a 
            JOIN users u ON a.user_id = u.user_id 
            WHERE a.admin_id = :id
        ", ["id" => $admin_id]);
    }

    public function update_admin($admin_id, $admin) {
        return $this->update($admin_id, $admin);
    }

    public function delete_admin($admin_id) {
        return $this->delete($admin_id);
    }
}
?>
