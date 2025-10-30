<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";

class ShelterDao extends BaseDao {
    public function __construct() {
        parent::__construct("shelters", "shelter_id");
    }

    public function create_shelter($shelter) {
        return $this->insert($shelter);
    }

    public function get_shelter_by_id($shelter_id) {
        return $this->get_by_id($shelter_id);
    }

    public function get_all_shelters() {
        return $this->get_all();
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

    public function update_shelter($shelter_id, $shelter) {
        return $this->update($shelter_id, $shelter);
    }

    public function delete_shelter($shelter_id) {
        return $this->delete($shelter_id);
    }
}
?>
