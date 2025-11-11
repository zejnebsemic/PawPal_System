<?php
/**
 * @OA\Get(
 *     path="/adoption-requests",
 *     tags={"adoption_requests"},
 *     summary="Get all adoption requests",
 *     @OA\Response(
 *         response=200,
 *         description="List of all adoption requests"
 *     )
 * )
 */
Flight::route('GET /adoption-requests', function() {
    Flight::json(Flight::adoptionRequestService()->get_all_requests());
});

/**
 * @OA\Get(
 *     path="/adoption-requests/{id}",
 *     tags={"adoption_requests"},
 *     summary="Get adoption request by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Adoption request ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns an adoption request by ID"
 *     )
 * )
 */
Flight::route('GET /adoption-requests/@id', function($id) {
    Flight::json(Flight::adoptionRequestService()->get_request_by_id($id));
});

/**
 * @OA\Get(
 *     path="/adoption-requests/user/{user_id}",
 *     tags={"adoption_requests"},
 *     summary="Get adoption requests by user ID",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="User ID whose requests to fetch",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of adoption requests by user"
 *     )
 * )
 */
Flight::route('GET /adoption-requests/user/@user_id', function($user_id) {
    Flight::json(Flight::adoptionRequestService()->get_requests_by_user($user_id));
});

/**
 * @OA\Get(
 *     path="/adoption-requests/pending",
 *     tags={"adoption_requests"},
 *     summary="Get all pending adoption requests",
 *     @OA\Response(
 *         response=200,
 *         description="List of pending requests"
 *     )
 * )
 */
Flight::route('GET /adoption-requests/pending', function() {
    Flight::json(Flight::adoptionRequestService()->get_pending_requests());
});

/**
 * @OA\Post(
 *     path="/adoption-requests",
 *     tags={"adoption_requests"},
 *     summary="Create a new adoption request",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "pet_id", "status"},
 *             @OA\Property(property="user_id", type="integer", example=2),
 *             @OA\Property(property="pet_id", type="integer", example=5),
 *             @OA\Property(property="status", type="string", example="pending")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Adoption request created successfully"
 *     )
 * )
 */
Flight::route('POST /adoption-requests', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::adoptionRequestService()->create_request($data));
});

/**
 * @OA\Put(
 *     path="/adoption-requests/{id}",
 *     tags={"adoption_requests"},
 *     summary="Update an existing adoption request",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Adoption request ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="approved")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Adoption request updated successfully"
 *     )
 * )
 */
Flight::route('PUT /adoption-requests/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::adoptionRequestService()->update_request($id, $data));
});

/**
 * @OA\Delete(
 *     path="/adoption-requests/{id}",
 *     tags={"adoption_requests"},
 *     summary="Delete adoption request by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Adoption request ID to delete",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Adoption request deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /adoption-requests/@id', function($id) {
    Flight::json(Flight::adoptionRequestService()->delete_request($id));
});
?>
