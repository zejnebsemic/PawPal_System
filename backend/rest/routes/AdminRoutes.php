<?php
/**
 * @OA\Get(
 *     path="/admin",
 *     tags={"admin"},
 *     summary="Get all admins",
 *     @OA\Response(
 *         response=200,
 *         description="List of all admins"
 *     )
 * )
 */
Flight::route('GET /admin', function() {
    Flight::json(Flight::adminService()->get_all_admins());
});

/**
 * @OA\Get(
 *     path="/admin/{id}",
 *     tags={"admin"},
 *     summary="Get admin by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Admin ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns admin data by ID"
 *     )
 * )
 */
Flight::route('GET /admin/@id', function($id) {
    Flight::json(Flight::adminService()->get_admin_by_id($id));
});

/**
 * @OA\Get(
 *     path="/admin/{id}/user-info",
 *     tags={"admin"},
 *     summary="Get admin with linked user info",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Admin ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Admin with user info"
 *     )
 * )
 */
Flight::route('GET /admin/@id/user-info', function($id) {
    Flight::json(Flight::adminService()->get_admin_with_user_info($id));
});

/**
 * @OA\Post(
 *     path="/admin",
 *     tags={"admin"},
 *     summary="Create new admin",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "role"},
 *             @OA\Property(property="user_id", type="integer", example=5),
 *             @OA\Property(property="role", type="string", example="super_admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Admin created successfully"
 *     )
 * )
 */
Flight::route('POST /admin', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::adminService()->create_admin($data));
});

/**
 * @OA\Put(
 *     path="/admin/{id}",
 *     tags={"admin"},
 *     summary="Update existing admin",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Admin ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="role", type="string", example="updated_role")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Admin updated successfully"
 *     )
 * )
 */
Flight::route('PUT /admin/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::adminService()->update_admin($id, $data));
});

/**
 * @OA\Delete(
 *     path="/admin/{id}",
 *     tags={"admin"},
 *     summary="Delete admin by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Admin ID to delete",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Admin deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /admin/@id', function($id) {
    Flight::json(Flight::adminService()->delete_admin($id));
});
?>
