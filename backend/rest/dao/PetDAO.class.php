<?php
require_once __DIR__ ."/../dao/BaseDao.class.php";

class PetDao extends BaseDao {
    public function __construct() {
        parent::__construct("pets", "pet_id");
    }

    public function get_pets_by_shelter($shelter_id) {
        return $this->query("SELECT * FROM pets WHERE shelter_id = :shelter_id", ["shelter_id" => $shelter_id]);
    }

    public function get_available_pets() {
        return $this->query("SELECT * FROM pets WHERE availability = 'available'", []);
    }

    public function get_pet_details($pet_id) {
        return $this->query_unique("
            SELECT p.*, s.name AS shelter_name
            FROM pets p
            JOIN shelters s ON p.shelter_id = s.shelter_id
            WHERE p.pet_id = :id
        ", ["id" => $pet_id]);
    }
}
