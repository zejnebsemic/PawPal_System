<?php

Flight::group('/shelters', function() {

    /**
     * @OA\Get(
     *     path="/shelters",
     *     tags={"shelters"},
     *     summary="Get all shelters",
     *     @OA\Response(
     *         response=200,
     *         description="List of all shelters"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('GET /', function() {
        try {
            $response = Flight::shelterService()->get_all_shelters();
            Flight::json([
                'success' => true,
                'data' => $response
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/shelters/{id}",
     *     tags={"shelters"},
     *     summary="Get shelter by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Shelter ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shelter details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shelter not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        try {
            $response = Flight::shelterService()->get_shelter_by_id($id);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'data' => $response['data']
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => "Shelter not found."
                ], 404);
            }
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/shelters/admins",
     *     tags={"shelters"},
     *     summary="Get all shelters with their admin names",
     *     @OA\Response(
     *         response=200,
     *         description="List of shelters with assigned admins"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('GET /admins', function() {
        try {
            $response = Flight::shelterService()->get_shelters_with_admins();
            Flight::json([
                'success' => true,
                'data' => $response
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/shelters/name/{name}",
     *     tags={"shelters"},
     *     summary="Get shelter by name",
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         description="Shelter name",
     *         @OA\Schema(type="string", example="Happy Paws Shelter")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shelter details by name"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shelter not found by name"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('GET /name/@name', function($name) {
        try {
            $response = Flight::shelterService()->get_shelter_by_name($name);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'data' => $response['data']
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => "Shelter not found by name."
                ], 404);
            }
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * @OA\Post(
     *     path="/shelters",
     *     tags={"shelters"},
     *     summary="Create a new shelter",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","location","admin_id"},
     *             @OA\Property(property="name", type="string", example="Happy Paws Shelter"),
     *             @OA\Property(property="location", type="string", example="Sarajevo, Bosnia"),
     *             @OA\Property(property="admin_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shelter created successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('POST /', function() {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::shelterService()->create_shelter($data);

            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Shelter created successfully',
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to create shelter.");
            }
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * @OA\Put(
     *     path="/shelters/{id}",
     *     tags={"shelters"},
     *     summary="Update an existing shelter",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Shelter ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Shelter Name"),
     *             @OA\Property(property="location", type="string", example="Updated Location"),
     *             @OA\Property(property="admin_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shelter updated successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('PUT /@id', function($id) {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::shelterService()->update_shelter($id, $data);

            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Shelter updated successfully',
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to update shelter.");
            }
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * @OA\Delete(
     *     path="/shelters/{id}",
     *     tags={"shelters"},
     *     summary="Delete shelter by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Shelter ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shelter deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        try {
            $response = Flight::shelterService()->delete_shelter($id);

            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Shelter deleted successfully'
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to delete shelter.");
            }
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

});
?>
