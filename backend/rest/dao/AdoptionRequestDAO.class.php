<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";

class AdoptionRequestDao extends BaseDao {
    public function __construct() {
        parent::__construct("adoption_requests", "request_id");
    }

    public function get_requests_by_user($user_id) {
        return $this->query("
            SELECT ar.*, p.name AS pet_name, s.name AS shelter_name
            FROM adoption_requests ar
            JOIN pets p ON ar.pet_id = p.pet_id
            JOIN shelters s ON p.shelter_id = s.shelter_id
            WHERE ar.user_id = :user_id
        ", ["user_id" => $user_id]);
    }

    public function get_pending_requests() {
        return $this->query("SELECT * FROM adoption_requests WHERE status = 'pending'", []);
    }
}
