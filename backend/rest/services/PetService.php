<?php
require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/PetDao.class.php";

class PetService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new PetDao());
    }

    public function get_pets_by_shelter($shelter_id)
    {
        return $this->dao->get_pets_by_shelter($shelter_id);
    }

    public function get_available_pets()
    {
        return $this->dao->get_available_pets();
    }

    public function get_pet_details($pet_id)
    {
        return $this->dao->get_pet_details($pet_id);
    }
}
