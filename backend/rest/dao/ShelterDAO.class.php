<?php
require_once __DIR__ ."/../dao/BaseDao.class.php";

class ShelterDao extends BaseDao {
    public function __construct() {
        parent::__construct("shelters", "shelter_id");
    }

    public function get_shelters_with_admins() {
        return $this->query("
            SELECT s.*, u.full_name AS admin_name
            FROM shelters s
            LEFT JOIN admins a ON s.admin_id = a.admin_id
            LEFT JOIN users u ON a.user_id = u.user_id
        ", []);
    }

    public function get_shelter_by_name($name) {
        return $this->query_unique("SELECT * FROM shelters WHERE name = :name", ["name" => $name]);
    }
}
