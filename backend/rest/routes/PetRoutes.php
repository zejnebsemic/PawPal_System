<?php
/**
 * @OA\Get(
 *     path="/pets",
 *     tags={"pets"},
 *     summary="Get all pets",
 *     @OA\Response(
 *         response=200,
 *         description="List of all pets"
 *     )
 * )
 */
Flight::route('GET /pets', function() {
    Flight::json(Flight::petService()->get_all_pets());
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
 *         description="Returns a pet by ID"
 *     )
 * )
 */
Flight::route('GET /pets/@id', function($id) {
    Flight::json(Flight::petService()->get_pet_by_id($id));
});

/**
 * @OA\Get(
 *     path="/pets/{id}/details",
 *     tags={"pets"},
 *     summary="Get detailed pet information including shelter name",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Pet ID",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns detailed pet info with shelter"
 *     )
 * )
 */
Flight::route('GET /pets/@id/details', function($id) {
    Flight::json(Flight::petService()->get_pet_details($id));
});

/**
 * @OA\Get(
 *     path="/pets/shelter/{shelter_id}",
 *     tags={"pets"},
 *     summary="Get pets by shelter ID",
 *     @OA\Parameter(
 *         name="shelter_id",
 *         in="path",
 *         required=true,
 *         description="Shelter ID",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of pets from a specific shelter"
 *     )
 * )
 */
Flight::route('GET /pets/shelter/@shelter_id', function($shelter_id) {
    Flight::json(Flight::petService()->get_pets_by_shelter($shelter_id));
});

/**
 * @OA\Get(
 *     path="/pets/available",
 *     tags={"pets"},
 *     summary="Get all available pets for adoption",
 *     @OA\Response(
 *         response=200,
 *         description="List of all available pets"
 *     )
 * )
 */
Flight::route('GET /pets/available', function() {
    Flight::json(Flight::petService()->get_available_pets());
});

/**
 * @OA\Post(
 *     path="/pets",
 *     tags={"pets"},
 *     summary="Create a new pet",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "species", "age", "shelter_id", "availability"},
 *             @OA\Property(property="name", type="string", example="Bella"),
 *             @OA\Property(property="species", type="string", example="Dog"),
 *             @OA\Property(property="age", type="integer", example=3),
 *             @OA\Property(property="shelter_id", type="integer", example=1),
 *             @OA\Property(property="availability", type="string", example="available")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Pet created successfully"
 *     )
 * )
 */
Flight::route('POST /pets', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::petService()->create_pet($data));
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
 *     )
 * )
 */
Flight::route('PUT /pets/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::petService()->update_pet($id, $data));
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
 *     )
 * )
 */
Flight::route('DELETE /pets/@id', function($id) {
    Flight::json(Flight::petService()->delete_pet($id));
});

?>
