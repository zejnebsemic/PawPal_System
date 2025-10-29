<?php
require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/ReviewDao.class.php";

class ReviewService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new ReviewDao());
    }

    public function get_reviews_by_shelter($shelter_id)
    {
        return $this->dao->get_reviews_by_shelter($shelter_id);
    }

    public function get_average_rating($shelter_id)
    {
        return $this->dao->get_average_rating($shelter_id);
    }
}
