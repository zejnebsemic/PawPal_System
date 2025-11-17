<?php

Flight::group('/admin', function() {

    /**
     * @OA\Get(
     *     path="/admin",
     *     tags={"admin"},
     *     summary="Get all admins",
     *     @OA\Response(response=200, description="List of all admins"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('GET /', function() {
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Admin details"),
     *     @OA\Response(response=404, description="Admin not found"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('GET /@id', function($id) {
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
     * @OA\Get(
     *     path="/admin/{id}/user-info",
     *     tags={"admin"},
     *     summary="Get admin with user info",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Admin with user info"),
     *     @OA\Response(response=404, description="Admin not found"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('GET /@id/user-info', function($id) {
        try {
            $response = Flight::adminService()->get_admin_with_user_info($id);
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="admin1"),
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Admin created successfully"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('POST /', function() {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::adminService()->create_admin($data);
            if ($response['success']) {
                Flight::json(['success' => true, 'message' => 'Admin created successfully', 'data' => $response['data']]);
            } else {
                throw new Exception($response['error'] ?? "Unable to create admin.");
            }
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Put(
     *     path="/admin/{id}",
     *     tags={"admin"},
     *     summary="Update an existing admin",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="username", type="string", example="updatedAdmin"),
     *         @OA\Property(property="email", type="string", example="updated@example.com")
     *     )),
     *     @OA\Response(response=200, description="Admin updated successfully"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('PUT /@id', function($id) {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::adminService()->update_admin($id, $data);
            if ($response['success']) {
                Flight::json(['success' => true, 'message' => 'Admin updated successfully', 'data' => $response['data']]);
            } else {
                throw new Exception($response['error'] ?? "Unable to update admin.");
            }
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Admin deleted successfully"),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        try {
            $response = Flight::adminService()->delete_admin($id);
            if ($response['success']) {
                Flight::json(['success' => true, 'message' => 'Admin deleted successfully']);
            } else {
                throw new Exception($response['error'] ?? "Unable to delete admin.");
            }
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

});
?>
