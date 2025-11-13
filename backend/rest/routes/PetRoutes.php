<?php

Flight::group('/pets', function() {

    /**
     * @OA\Get(
     *     path="/pets",
     *     tags={"pets"},
     *     summary="Get all pets",
     *     @OA\Response(
     *         response=200,
     *         description="List of all pets"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /', function() {
        try {
            $response = Flight::petService()->get_all_pets();
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
     *     path="/pets/{id}",
     *     tags={"pets"},
     *     summary="Get pet by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Pet ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet details"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        try {
            $response = Flight::petService()->get_pet_by_id($id);
            if ($response) {
                Flight::json([
                    'success' => true,
                    'data' => $response
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => "Pet not found."
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
     *     path="/pets",
     *     tags={"pets"},
     *     summary="Create a new pet",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","type","age","shelter_id","availability"},
     *             @OA\Property(property="name", type="string", example="Bella"),
     *             @OA\Property(property="type", type="string", example="dog"),
     *             @OA\Property(property="age", type="integer", example=3),
     *             @OA\Property(property="shelter_id", type="integer", example=1),
     *             @OA\Property(property="availability", type="string", example="available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet created successfully"
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
            $response = Flight::petService()->create_pet($data);

            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Pet added successfully',
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to create pet.");
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
     *     path="/pets/{id}",
     *     tags={"pets"},
     *     summary="Update an existing pet",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Pet ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Bella"),
     *             @OA\Property(property="availability", type="string", example="adopted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet updated successfully"
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
            $response = Flight::petService()->update_pet($id, $data);

            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Pet updated successfully',
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to update pet.");
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
     *     path="/pets/{id}",
     *     tags={"pets"},
     *     summary="Delete pet by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Pet ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        try {
            $response = Flight::petService()->delete_pet($id);

            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Pet deleted successfully'
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to delete pet.");
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
