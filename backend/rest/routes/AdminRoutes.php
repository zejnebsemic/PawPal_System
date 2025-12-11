<?php

Flight::group('/admin', function() {

    /**
     * @OA\Get(
     *     path="/admin",
     *     tags={"admin"},
     *     summary="Get all admins",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="List of all admins"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('GET /', function() {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            $response = Flight::adminService()->get_all_admins();
            Flight::json([
                'success' => true,
                'data' => $response['data'] ?? []
            ]);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/admin/{id}",
     *     tags={"admin"},
     *     summary="Get admin by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Admin details"),
     *     @OA\Response(response=404, description="Admin not found"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('GET /@id', function($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            $response = Flight::adminService()->get_admin_by_id($id);
            if ($response['success']) {
                Flight::json(['success' => true, 'data' => $response['data']]);
            } else {
                Flight::json(['success' => false, 'error' => $response['error']], 404);
            }
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Post(
     *     path="/admin",
     *     tags={"admin"},
     *     summary="Create a new admin",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="admin1"),
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Admin created successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('POST /', function() {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN); 
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::adminService()->create_admin($data);
            Flight::json(['success' => true, 'message' => 'Admin created successfully', 'data' => $response['data']]);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Delete(
     *     path="/admin/{id}",
     *     tags={"admin"},
     *     summary="Delete admin by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Admin deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN); 
        try {
            $response = Flight::adminService()->delete_admin($id);
            Flight::json(['success' => true, 'message' => 'Admin deleted successfully']);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/admin/dashboard",
     *     tags={"admin"},
     *     summary="Admin dashboard",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Admin dashboard message"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('GET /dashboard', function() {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN); 
        Flight::json(['message' => 'Dobrodošao na Admin dashboard']);
    });

});
?>
