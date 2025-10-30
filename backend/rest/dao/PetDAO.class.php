<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";

class PetDao extends BaseDao {
    public function __construct() {
        parent::__construct("pets", "pet_id");
    }

    public function create_pet($pet) {
        return $this->insert($pet);
    }

    public function get_pet_by_id($pet_id) {
        return $this->get_by_id($pet_id);
    }

    public function get_all_pets() {
        return $this->get_all();
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

    public function update_pet($pet_id, $pet) {
        return $this->update($pet_id, $pet);
    }

    public function delete_pet($pet_id) {
        return $this->delete($pet_id);
    }
}
?>
