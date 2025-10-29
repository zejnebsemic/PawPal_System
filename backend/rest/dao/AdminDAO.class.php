<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";

class AdminDao extends BaseDao {
    public function __construct() {
        parent::__construct("admins", "admin_id");
    }

    public function get_admin_with_user_info($admin_id) {
        return $this->query_unique("
            SELECT a.*, u.full_name, u.email 
            FROM admins a 
            JOIN users u ON a.user_id = u.user_id 
            WHERE a.admin_id = :id
        ", ["id" => $admin_id]);
    }
}
