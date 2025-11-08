<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/ReviewDAO.php');

class ReviewService extends BaseService {

    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function create_review($review) {
        return $this->dao->create_review($review);
    }

    public function get_review_by_id($review_id) {
        return $this->dao->get_review_by_id($review_id);
    }

    public function get_all_reviews() {
        return $this->dao->get_all_reviews();
    }

    public function get_reviews_by_shelter($shelter_id) {
        return $this->dao->get_reviews_by_shelter($shelter_id);
    }

    public function get_average_rating($shelter_id) {
        return $this->dao->get_average_rating($shelter_id);
    }

    public function update_review($review_id, $review) {
        return $this->dao->update_review($review_id, $review);
    }

    public function delete_review($review_id) {
        return $this->dao->delete_review($review_id);
    }
}
?>
