<?php
require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/AdminDao.class.php";

class AdminService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new AdminDao());
    }

    public function get_admin_with_user_info($admin_id)
    {
        return $this->dao->get_admin_with_user_info($admin_id);
    }
}
