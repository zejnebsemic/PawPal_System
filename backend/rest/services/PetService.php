<?php
require_once __DIR__ . '/BaseService.php';
require_once(__DIR__ . '/../dao/PetDAO.php');

class PetService extends BaseService {

    public function __construct() {
        $dao = new PetDao();
        parent::__construct($dao);
    }

    public function create_pet($pet) {
    $result = $this->dao->create_pet($pet);
    if ($result) {
        $pet_id = $this->dao->getConnection()->lastInsertId();
        $pet['pet_id'] = $pet_id;
        return ['success' => true, 'data' => $pet];
    } else {
        return ['success' => false, 'error' => 'Unable to create pet.'];
    }
}

    public function get_pet_by_id($pet_id) {
        try {
            $pet = $this->dao->get_pet_by_id($pet_id);
            if ($pet) {
                return ['success' => true, 'data' => $pet];
            } else {
                return ['success' => false, 'error' => 'Pet not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_all_pets() {
        try {
            $pets = $this->dao->get_all_pets();
            return ['success' => true, 'data' => $pets ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_pets_by_shelter($shelter_id) {
        try {
            $pets = $this->dao->get_pets_by_shelter($shelter_id);
            return ['success' => true, 'data' => $pets ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_available_pets() {
        try {
            $pets = $this->dao->get_available_pets();
            return ['success' => true, 'data' => $pets ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_pet_details($pet_id) {
        try {
            $pet = $this->dao->get_pet_details($pet_id);
            if ($pet) {
                return ['success' => true, 'data' => $pet];
            } else {
                return ['success' => false, 'error' => 'Pet details not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function update_pet($pet_id, $pet) {
        try {
            $success = $this->dao->update_pet($pet_id, $pet);
            if ($success) {
                return ['success' => true, 'data' => $this->dao->get_pet_by_id($pet_id)];
            } else {
                return ['success' => false, 'error' => 'Unable to update pet.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function delete_pet($pet_id) {
        try {
            $success = $this->dao->delete_pet($pet_id);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Unable to delete pet.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }
}
?>
