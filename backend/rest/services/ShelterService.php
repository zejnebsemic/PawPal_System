<?php
require_once __DIR__ . '/BaseService.php';
require_once(__DIR__ . '/../dao/ShelterDAO.php');

class ShelterService extends BaseService {

    public function __construct() {
        $dao = new ShelterDao();
        parent::__construct($dao);
    }

    public function create_shelter($shelter) {
        try {
            $result = $this->dao->create_shelter($shelter);
            if ($result) {
                $shelter_id = $this->dao->getConnection()->lastInsertId();
                $shelter['shelter_id'] = $shelter_id;
                return ['success' => true, 'data' => $shelter];
            } else {
                return ['success' => false, 'error' => 'Unable to create shelter.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_shelter_by_id($shelter_id) {
        try {
            $shelter = $this->dao->get_shelter_by_id($shelter_id);
            if ($shelter) {
                return ['success' => true, 'data' => $shelter];
            } else {
                return ['success' => false, 'error' => 'Shelter not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_all_shelters() {
        try {
            $shelters = $this->dao->get_all_shelters();
            return ['success' => true, 'data' => $shelters ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_shelters_with_admins() {
        try {
            $shelters = $this->dao->get_shelters_with_admins();
            return ['success' => true, 'data' => $shelters ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_shelter_by_name($name) {
        try {
            $shelter = $this->dao->get_shelter_by_name($name);
            if ($shelter) {
                return ['success' => true, 'data' => $shelter];
            } else {
                return ['success' => false, 'error' => 'Shelter not found by name.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function update_shelter($shelter_id, $shelter) {
        try {
            $success = $this->dao->update_shelter($shelter_id, $shelter);
            if ($success) {
                return ['success' => true, 'data' => $this->dao->get_shelter_by_id($shelter_id)];
            } else {
                return ['success' => false, 'error' => 'Unable to update shelter.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function delete_shelter($shelter_id) {
        try {
            $success = $this->dao->delete_shelter($shelter_id);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Unable to delete shelter.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }
}
?>
