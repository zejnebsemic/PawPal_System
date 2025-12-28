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
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        Flight::json(Flight::adoptionRequestService()->get_all_requests());
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Get adoption request by ID (admin)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Adoption request details"),
     *     @OA\Response(response=404, description="Request not found")
     * )
     */
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        Flight::json(Flight::adoptionRequestService()->get_request_by_id($id));
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/user",
     *     tags={"adoption-requests"},
     *     summary="Get adoption requests for logged-in user",
     *     @OA\Response(response=200, description="User adoption requests"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('GET /user', function () {
        Flight::auth_middleware()->authorizeRole(Roles::USER);

        $user = Flight::get('user');

        Flight::json(
            Flight::adoptionRequestService()
                ->get_requests_by_user($user->user_id)
        );
    });

    /**
     * @OA\Get(
     *     path="/adoption-requests/pending",
     *     tags={"adoption-requests"},
     *     summary="Get all pending adoption requests (admin)",
     *     @OA\Response(response=200, description="Pending requests")
     * )
     */
    Flight::route('GET /pending', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        Flight::json(Flight::adoptionRequestService()->get_pending_requests());
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
     *     @OA\Response(response=200, description="Adoption request created"),
     *     @OA\Response(response=400, description="Invalid payload")
     * )
     */
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::USER);

        $data = Flight::request()->data->getData();

        if (empty($data['pet_id'])) {
            Flight::json([
                'success' => false,
                'error' => 'pet_id is required'
            ], 400);
            return;
        }

        $user = Flight::get('user');

        $data['user_id'] = $user->user_id;
        $data['status']  = 'pending';

        Flight::json(
            Flight::adoptionRequestService()->create_request($data)
        );
    });

    /**
     * @OA\Put(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Update adoption request status (admin)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="approved")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Request updated")
     * )
     */
    Flight::route('PUT /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $data = Flight::request()->data->getData();

        if (empty($data['status'])) {
            Flight::json([
                'success' => false,
                'error' => 'status is required'
            ], 400);
            return;
        }

        $data['processed_at'] = date('Y-m-d H:i:s');

        Flight::json(
            Flight::adoptionRequestService()->update_request($id, $data)
        );
    });

    /**
     * @OA\Delete(
     *     path="/adoption-requests/{id}",
     *     tags={"adoption-requests"},
     *     summary="Delete adoption request (admin)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Request deleted")
     * )
     */
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        Flight::json(
            Flight::adoptionRequestService()->delete_request($id)
        );
    });
});
