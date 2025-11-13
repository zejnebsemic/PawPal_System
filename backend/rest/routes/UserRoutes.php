<?php

Flight::group('/users', function() {

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"users"},
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="List of all users"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /', function() {
        try {
            $response = Flight::userService()->get_all_users();
            Flight::json($response);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"users"},
     *     summary="Get user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        try {
            $response = Flight::userService()->get_user_by_id($id);
            Flight::json($response);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/users/email/{email}",
     *     tags={"users"},
     *     summary="Get user by email",
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         required=true,
     *         description="User email",
     *         @OA\Schema(type="string", example="user@test.com")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details by email"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /email/@email', function($email) {
        try {
            $response = Flight::userService()->get_user_by_email($email);
            Flight::json($response);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"users"},
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name","email","password_hash"},
     *             @OA\Property(property="full_name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@test.com"),
     *             @OA\Property(property="password_hash", type="string", example="hashedpassword"),
     *             @OA\Property(property="role", type="string", example="user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('POST /', function() {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::userService()->create_user($data);
            Flight::json($response);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"users"},
     *     summary="Update an existing user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="full_name", type="string", example="Updated Name"),
     *             @OA\Property(property="email", type="string", example="updated@test.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('PUT /@id', function($id) {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::userService()->update_user($id, $data);
            Flight::json($response);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"users"},
     *     summary="Delete user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        try {
            $response = Flight::userService()->delete_user($id);
            Flight::json($response);
        } catch (PDOException $e) {
            Flight::json(['success' => false, 'error' => "Database error: " . $e->getMessage()], 500);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

});
?>
