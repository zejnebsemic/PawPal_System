<?php
require_once __DIR__ . 'BaseService.php';
require_once __DIR__ . '/../dao/AdoptionRequestDAO.php';

class AdoptionRequestService extends BaseService {
    public function __construct() {
        parent::__construct(new AdoptionRequestDao());
    }

    public function create_request($request) {
        return $this->dao->create_request($request);
    }

    public function get_request_by_id($request_id) {
        return $this->dao->get_request_by_id($request_id);
    }

    public function get_all_requests() {
        return $this->dao->get_all_requests();
    }

    public function get_requests_by_user($user_id) {
        return $this->dao->get_requests_by_user($user_id);
    }

    public function get_pending_requests() {
        return $this->dao->get_pending_requests();
    }

    public function update_request($request_id, $request) {
        return $this->dao->update_request($request_id, $request);
    }

    public function delete_request($request_id) {
        return $this->dao->delete_request($request_id);
    }
}
?>
