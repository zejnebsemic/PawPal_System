<?php
/**
 * @OA\Get(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Get all reviews",
 *     @OA\Response(
 *         response=200,
 *         description="List of all reviews"
 *     )
 * )
 */
Flight::route('GET /reviews', function() {
    Flight::json(Flight::reviewService()->get_all_reviews());
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
 *         description="Returns a specific review"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function($id) {
    Flight::json(Flight::reviewService()->get_review_by_id($id));
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
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reviews for the given shelter"
 *     )
 * )
 */
Flight::route('GET /reviews/shelter/@shelter_id', function($shelter_id) {
    Flight::json(Flight::reviewService()->get_reviews_by_shelter($shelter_id));
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
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Average rating value for the given shelter"
 *     )
 * )
 */
Flight::route('GET /reviews/shelter/@shelter_id/average', function($shelter_id) {
    Flight::json(Flight::reviewService()->get_average_rating($shelter_id));
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Create a new review",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "shelter_id", "rating", "comment"},
 *             @OA\Property(property="user_id", type="integer", example=2),
 *             @OA\Property(property="shelter_id", type="integer", example=1),
 *             @OA\Property(property="rating", type="number", format="float", example=4.5),
 *             @OA\Property(property="comment", type="string", example="Very caring staff and clean shelter!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review created successfully"
 *     )
 * )
 */
Flight::route('POST /reviews', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->create_review($data));
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
 *             @OA\Property(property="comment", type="string", example="Updated comment after visit.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review updated successfully"
 *     )
 * )
 */
Flight::route('PUT /reviews/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update_review($id, $data));
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
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function($id) {
    Flight::json(Flight::reviewService()->delete_review($id));
});
?>
