<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AdoptionRequestDAO.php';

class AdoptionRequestService extends BaseService {

    public function __construct() {
        $dao = new AdoptionRequestDao();
        parent::__construct($dao);
    }

    public function create_request($request) {
        try {
            $result = $this->dao->create_request($request);
            if ($result) {
                $request_id = $this->dao->getConnection()->lastInsertId();
                $request['request_id'] = $request_id;
                return ['success' => true, 'data' => $request];
            } else {
                return ['success' => false, 'error' => 'Unable to create adoption request.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function get_request_by_id($request_id) {
        try {
            $request = $this->dao->get_request_by_id($request_id);
            if ($request) {
                return ['success' => true, 'data' => $request];
            } else {
                return ['success' => false, 'error' => 'Adoption request not found.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_all_requests() {
        try {
            $requests = $this->dao->get_all_requests();
            return ['success' => true, 'data' => $requests ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_requests_by_user($user_id) {
        try {
            $requests = $this->dao->get_requests_by_user($user_id);
            return ['success' => true, 'data' => $requests ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function get_pending_requests() {
        try {
            $requests = $this->dao->get_pending_requests();
            return ['success' => true, 'data' => $requests ?? []];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function update_request($request_id, $request) {
        try {
            $success = $this->dao->update_request($request_id, $request);
            if ($success) {
                return ['success' => true, 'data' => $this->dao->get_request_by_id($request_id)];
            } else {
                return ['success' => false, 'error' => 'Unable to update adoption request.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }

    public function delete_request($request_id) {
        try {
            $success = $this->dao->delete_request($request_id);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Unable to delete adoption request.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
        }
    }
}
?>
