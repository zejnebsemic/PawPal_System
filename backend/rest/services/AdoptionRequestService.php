<?php
require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/AdoptionRequestDao.class.php";

class AdoptionRequestService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new AdoptionRequestDao());
    }

    public function get_requests_by_user($user_id)
    {
        return $this->dao->get_requests_by_user($user_id);
    }

    public function get_requests_by_shelter($shelter_id)
    {
        return $this->dao->get_requests_by_shelter($shelter_id);
    }
}
