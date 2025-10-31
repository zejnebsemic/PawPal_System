<?php
require_once __DIR__ . "/BaseDao.php";

class ReviewDao extends BaseDao {
    public function __construct() {
        parent::__construct("reviews", "review_id");
    }

    public function create_review($review) {
        return $this->insert($review);
    }

    public function get_review_by_id($review_id) {
        return $this->getById($review_id);
    }

    public function get_all_reviews() {
        return $this->getAll();
    }

    public function get_reviews_by_shelter($shelter_id) {
        return $this->query("
            SELECT r.*, u.full_name AS user_name
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            WHERE r.shelter_id = :shelter_id
            ORDER BY r.created_at DESC
        ", ["shelter_id" => $shelter_id]);
    }

    public function get_average_rating($shelter_id) {
        return $this->query_unique("
            SELECT AVG(rating) AS avg_rating
            FROM reviews
            WHERE shelter_id = :shelter_id
        ", ["shelter_id" => $shelter_id]);
    }

    public function update_review($review_id, $review) {
        return $this->update($review_id, $review);
    }

    public function delete_review($review_id) {
        return $this->delete($review_id);
    }
}
?>
