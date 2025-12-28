<?php
require_once __DIR__ . '/../../data/roles.php';

Flight::group('/adoption-requests', function () {

    /**
     * @OA\Get(
     *     path="/adoption-requests",
     *     tags={"adoption-requests"},
     *     summary="Get all adoption requests (admin)",
     *     @OA\Response(response=200, description="List of all adoption requests"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->get_all_requests();
        if (!empty($response['success'])) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error'] ?? 'Internal server error'], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Get adoption request by ID (admin)",
     *     @OA\Parameter(name="id", in="path", required=true, description="Request ID", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Adoption request details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Request not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->get_request_by_id($id);
        if (!empty($response['success'])) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error'] ?? 'Request not found'], 404);
        }
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/my",
     *     tags={"adoption-requests"},
     *     summary="Get adoption requests for currently logged-in user",
     *     @OA\Response(response=200, description="List of adoption requests for current user"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /my', function () {
        Flight::auth_middleware()->authorizeRole(Roles::USER);

        
        $user = Flight::get('user');
        $user_id = $user['id'];

        $response = Flight::adoptionRequestService()->get_requests_by_user($user_id);
        if (!empty($response['success'])) {
            Flight::json($response);
        } else {
            
            Flight::json(['success' => true, 'data' => []]);
        }
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/user/{user_id}",
     *     tags={"adoption-requests"},
     *     summary="Get all adoption requests for a specific user (admin)",
     *     @OA\Parameter(name="user_id", in="path", required=true, description="User ID", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="List of adoption requests"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="No requests found for user"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /user/@user_id', function ($user_id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->get_requests_by_user($user_id);
        if (!empty($response['success'])) {
            Flight::json($response);
        } else {
            Flight::json(['success' => true, 'data' => []]);
        }
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/pending",
     *     tags={"adoption-requests"},
     *     summary="Get all pending adoption requests (admin)",
     *     @OA\Response(response=200, description="List of pending requests"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /pending', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->get_pending_requests();
        if (!empty($response['success'])) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error'] ?? 'Internal server error'], 500);
        }
    });

    /**
     * @OA\Post(
     *     path="/adoption-requests",
     *     tags={"adoption-requests"},
     *     summary="Create a new adoption request (user)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pet_id"},
     *             @OA\Property(property="pet_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Adoption request created successfully"),
     *     @OA\Response(response=400, description="Invalid payload"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::USER);

        
        $rawBody = Flight::request()->getBody();
        $data = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
            $data = Flight::request()->data->getData();
        }

        if (empty($data['pet_id'])) {
            Flight::json(['success' => false, 'error' => 'pet_id is required'], 400);
            return;
        }

        
        $user = Flight::get('user');
        $data['user_id'] = $user['id'];

        
        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }

        $response = Flight::adoptionRequestService()->create_request($data);
        if (!empty($response['success'])) {
            Flight::json([
                'success' => true,
                'message' => 'Adoption request created successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::json(['success' => false, 'error' => $response['error'] ?? 'Internal server error'], 500);
        }
    });

    /**
     * @OA\Put(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Update an adoption request (admin)",
     *     @OA\Parameter(name="id", in="path", required=true, description="Request ID", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="approved")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Adoption request updated successfully"),
     *     @OA\Response(response=400, description="Invalid payload"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('PUT /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $data = Flight::request()->data->getData();

        if (empty($data['status'])) {
            Flight::json(['success' => false, 'error' => 'status is required'], 400);
            return;
        }

        if (!isset($data['processed_at'])) {
            $data['processed_at'] = date('Y-m-d H:i:s');
        }

        $response = Flight::adoptionRequestService()->update_request($id, $data);
        if (!empty($response['success'])) {
            Flight::json([
                'success' => true,
                'message' => 'Adoption request updated successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::json(['success' => false, 'error' => $response['error'] ?? 'Internal server error'], 500);
        }
    });

    /**
     * @OA\Delete(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Delete an adoption request (admin)",
     *     @OA\Parameter(name="id", in="path", required=true, description="Request ID", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Adoption request deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->delete_request($id);
        if (!empty($response['success'])) {
            Flight::json([
                'success' => true,
                'message' => 'Adoption request deleted successfully'
            ]);
        } else {
            Flight::json(['success' => false, 'error' => $response['error'] ?? 'Internal server error'], 500);
        }
    });

});
