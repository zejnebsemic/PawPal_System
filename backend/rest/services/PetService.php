<?php
require_once __DIR__ . '/BaseService.php';
require_once(__DIR__ . '/../dao/PetDAO.php');

class PetService extends BaseService {

    public function __construct() {
        $dao = new PetDao();
        parent::__construct($dao);
    }

    public function create_pet($pet) {
        return $this->dao->create_pet($pet);
    }

    public function get_pet_by_id($pet_id) {
        return $this->dao->get_pet_by_id($pet_id);
    }

    public function get_all_pets() {
        return $this->dao->get_all_pets();
    }

    public function get_pets_by_shelter($shelter_id) {
        return $this->dao->get_pets_by_shelter($shelter_id);
    }

    public function get_available_pets() {
        return $this->dao->get_available_pets();
    }

    public function get_pet_details($pet_id) {
        return $this->dao->get_pet_details($pet_id);
    }

    public function update_pet($pet_id, $pet) {
        return $this->dao->update_pet($pet_id, $pet);
    }

    public function delete_pet($pet_id) {
        return $this->dao->delete_pet($pet_id);
    }
}
?>
