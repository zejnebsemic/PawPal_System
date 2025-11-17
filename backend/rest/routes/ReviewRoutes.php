<?php

Flight::group('/reviews', function() {

    /**
     * @OA\Get(
     *     path="/reviews",
     *     tags={"reviews"},
     *     summary="Get all reviews",
     *     @OA\Response(
     *         response=200,
     *         description="List of all reviews"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /', function() {
        try {
            $response = Flight::reviewService()->get_all_reviews();
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to fetch reviews.");
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
     *     path="/reviews/{id}",
     *     tags={"reviews"},
     *     summary="Get review by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Review ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review details"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        try {
            $response = Flight::reviewService()->get_review_by_id($id);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'data' => $response['data']
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => "Review not found."
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
     *     path="/reviews/shelter/{shelter_id}",
     *     tags={"reviews"},
     *     summary="Get all reviews for a specific shelter",
     *     @OA\Parameter(
     *         name="shelter_id",
     *         in="path",
     *         required=true,
     *         description="Shelter ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews for the given shelter"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /shelter/@shelter_id', function($shelter_id) {
        try {
            $response = Flight::reviewService()->get_reviews_by_shelter($shelter_id);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to fetch reviews for this shelter.");
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
     *     path="/reviews/shelter/{shelter_id}/average",
     *     tags={"reviews"},
     *     summary="Get average rating for a shelter",
     *     @OA\Parameter(
     *         name="shelter_id",
     *         in="path",
     *         required=true,
     *         description="Shelter ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Average rating value for the given shelter"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('GET /shelter/@shelter_id/average', function($shelter_id) {
        try {
            $response = Flight::reviewService()->get_average_rating($shelter_id);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to fetch average rating.");
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
     *     path="/reviews",
     *     tags={"reviews"},
     *     summary="Create a new review",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","shelter_id","rating","comment"},
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="shelter_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="number", format="float", example=4.5),
     *             @OA\Property(property="comment", type="string", example="Very caring staff!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review created successfully"
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
            $response = Flight::reviewService()->create_review($data);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Review added successfully',
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to create review.");
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
     *     path="/reviews/{id}",
     *     tags={"reviews"},
     *     summary="Update an existing review",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Review ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="number", example=5),
     *             @OA\Property(property="comment", type="string", example="Updated comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated successfully"
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
            $response = Flight::reviewService()->update_review($id, $data);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Review updated successfully',
                    'data' => $response['data']
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to update review.");
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
     *     path="/reviews/{id}",
     *     tags={"reviews"},
     *     summary="Delete a review by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Review ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        try {
            $response = Flight::reviewService()->delete_review($id);
            if (isset($response['success']) && $response['success']) {
                Flight::json([
                    'success' => true,
                    'message' => 'Review deleted successfully'
                ]);
            } else {
                throw new Exception($response['error'] ?? "Unable to delete review.");
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
