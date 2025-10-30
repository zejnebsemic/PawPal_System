<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";

class AdoptionRequestDao extends BaseDao {
    public function __construct() {
        parent::__construct("adoption_requests", "request_id");
    }

    public function create_request($request) {
        return $this->insert($request);
    }

    public function get_request_by_id($request_id) {
        return $this->get_by_id($request_id);
    }

    public function get_all_requests() {
        return $this->get_all();
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

    public function update_request($request_id, $request) {
        return $this->update($request_id, $request);
    }

    public function delete_request($request_id) {
        return $this->delete($request_id);
    }
}
?>
