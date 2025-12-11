<?php

Flight::group('/adoption-requests', function() {

    /**
     * @OA\Get(
     *     path="/adoption-requests",
     *     tags={"adoption-requests"},
     *     summary="Get all adoption requests",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="List of all adoption requests"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /', function() {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->get_all_requests();
        if ($response['success']) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Get adoption request by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Request ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Adoption request details"),
     *     @OA\Response(response=404, description="Request not found")
     * )
     */
    Flight::route('GET /@id', function($id) {
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->verifyToken(Flight::request()->getHeader('Authentication'));
        
        $user = Flight::get('user');
        
        $request = Flight::adoptionRequestService()->get_request_by_id($id);
        if ($user->role !== Roles::ADMIN && 
            isset($request['data']['user_id']) && 
            $request['data']['user_id'] != $user->id) {
            Flight::halt(403, 'Access denied: You can only view your own requests');
        }
        
        $response = Flight::adoptionRequestService()->get_request_by_id($id);
        if ($response['success']) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 404);
        }
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/user/{user_id}",
     *     tags={"adoption-requests"},
     *     summary="Get all adoption requests for a specific user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="List of adoption requests"),
     *     @OA\Response(response=404, description="No requests found for user")
     * )
     */
    Flight::route('GET /user/@user_id', function($user_id) {
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->verifyToken(Flight::request()->getHeader('Authentication'));
        
        $user = Flight::get('user');
        
        if ($user->role !== Roles::ADMIN && $user->id != $user_id) {
            Flight::halt(403, 'Access denied: You can only view your own requests');
        }
        
        $response = Flight::adoptionRequestService()->get_requests_by_user($user_id);
        if ($response['success']) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 404);
        }
    });
    /**
     * @OA\Get(
     *     path="/adoption-requests/pending",
     *     tags={"adoption-requests"},
     *     summary="Get all pending adoption requests",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="List of pending requests"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('GET /pending', function() {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $response = Flight::adoptionRequestService()->get_pending_requests();
        if ($response['success']) {
            Flight::json($response);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 500);
        }
    });

    /**
     * @OA\Post(
     *     path="/adoption-requests",
     *     tags={"adoption-requests"},
     *     summary="Create a new adoption request",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pet_id","user_id","status"},
     *             @OA\Property(property="pet_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Adoption request created successfully"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
      Flight::route('POST /', function() {
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->verifyToken(Flight::request()->getHeader('Authentication'));
        
        $data = Flight::request()->data->getData();
        
        $user = Flight::get('user');
        if ($user->role !== Roles::ADMIN && isset($data['user_id']) && $data['user_id'] != $user->id) {
            Flight::halt(403, 'Access denied: You can only create requests for yourself');
        }
        
        $response = Flight::adoptionRequestService()->create_request($data);
        if ($response['success']) {
            Flight::json([
                'success' => true,
                'message' => 'Adoption request created successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 500);
        }
    });

    /**
     * @OA\Put(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Update an adoption request",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Request ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="approved")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Adoption request updated successfully"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->verifyToken(Flight::request()->getHeader('Authentication'));
        
        $user = Flight::get('user');
        
        if ($user->role !== Roles::ADMIN) {
            Flight::halt(403, 'Access denied: Only admins can update request status');
        }
        
        $data = Flight::request()->data->getData();
        $response = Flight::adoptionRequestService()->update_request($id, $data);
        if ($response['success']) {
            Flight::json([
                'success' => true,
                'message' => 'Adoption request updated successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 500);
        }
    });


    /**
     * @OA\Delete(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Delete an adoption request",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Request ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Adoption request deleted successfully"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->verifyToken(Flight::request()->getHeader('Authentication'));
        
        $user = Flight::get('user');
        
        $request = Flight::adoptionRequestService()->get_request_by_id($id);
        if ($user->role !== Roles::ADMIN && 
            isset($request['data']['user_id']) && 
            $request['data']['user_id'] != $user->id) {
            Flight::halt(403, 'Access denied: You can only delete your own requests');
        }
        
        $response = Flight::adoptionRequestService()->delete_request($id);
        if ($response['success']) {
            Flight::json([
                'success' => true,
                'message' => 'Adoption request deleted successfully'
            ]);
        } else {
            Flight::json(['success' => false, 'error' => $response['error']], 500);
        }
    });
});
?>
