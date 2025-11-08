<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/ShelterDAO.php');

class ShelterService extends BaseService {

    public function __construct() {
        $dao = new ShelterDao();
        parent::__construct($dao);
    }

    public function create_shelter($shelter) {
        return $this->dao->create_shelter($shelter);
    }

    public function get_shelter_by_id($shelter_id) {
        return $this->dao->get_shelter_by_id($shelter_id);
    }

    public function get_all_shelters() {
        return $this->dao->get_all_shelters();
    }

    public function get_shelters_with_admins() {
        return $this->dao->get_shelters_with_admins();
    }

    public function get_shelter_by_name($name) {
        return $this->dao->get_shelter_by_name($name);
    }

    public function update_shelter($shelter_id, $shelter) {
        return $this->dao->update_shelter($shelter_id, $shelter);
    }

    public function delete_shelter($shelter_id) {
        return $this->dao->delete_shelter($shelter_id);
    }
}
?>
