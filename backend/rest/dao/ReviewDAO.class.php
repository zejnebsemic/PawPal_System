<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";

class ReviewDao extends BaseDao {
    public function __construct() {
        parent::__construct("reviews", "review_id");
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
}
