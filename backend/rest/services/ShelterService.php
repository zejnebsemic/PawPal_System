<?php
require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/ShelterDao.class.php";

class ShelterService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new ShelterDao());
    }

    public function get_shelters_with_admins()
    {
        return $this->dao->get_shelters_with_admins();
    }

    public function get_shelter_by_name($name)
    {
        return $this->dao->get_shelter_by_name($name);
    }
}
