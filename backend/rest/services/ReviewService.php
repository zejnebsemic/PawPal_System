<?php
require_once __DIR__ . '/BaseService.php';
require_once(__DIR__ . '/../dao/ReviewDAO.php');

class ReviewService extends BaseService {

    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function create_review($review) {
        try {
            $result = $this->dao->create_review($review);
            if ($result) {
                $review_id = $this->dao->getConnection()->lastInsertId();
                $review['review_id'] = $review_id;
                return ['success' => true, 'data' => $review];
            } else {
                return ['success' => false, 'error' => 'Unable to create review.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_review_by_id($review_id) {
        try {
            $review = $this->dao->get_review_by_id($review_id);
            if ($review) {
                return ['success' => true, 'data' => $review];
            } else {
                return ['success' => false, 'error' => 'Review not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_all_reviews() {
        try {
            $reviews = $this->dao->get_all_reviews();
            return ['success' => true, 'data' => $reviews ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_reviews_by_shelter($shelter_id) {
        try {
            $reviews = $this->dao->get_reviews_by_shelter($shelter_id);
            return ['success' => true, 'data' => $reviews ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_average_rating($shelter_id) {
        try {
            $rating = $this->dao->get_average_rating($shelter_id);
            return ['success' => true, 'data' => $rating ?? 0];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function update_review($review_id, $review) {
        try {
            $success = $this->dao->update_review($review_id, $review);
            if ($success) {
                return ['success' => true, 'data' => $this->dao->get_review_by_id($review_id)];
            } else {
                return ['success' => false, 'error' => 'Unable to update review.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function delete_review($review_id) {
        try {
            $success = $this->dao->delete_review($review_id);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Unable to delete review.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }
}
?>
