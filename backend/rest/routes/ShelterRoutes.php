<?php
/**
 * @OA\Get(
 *     path="/shelters",
 *     tags={"shelters"},
 *     summary="Get all shelters",
 *     @OA\Response(
 *         response=200,
 *         description="List of all shelters"
 *     )
 * )
 */
Flight::route('GET /shelters', function() {
    Flight::json(Flight::shelterService()->get_all_shelters());
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
 *         description="Returns a specific shelter"
 *     )
 * )
 */
Flight::route('GET /shelters/@id', function($id) {
    Flight::json(Flight::shelterService()->get_shelter_by_id($id));
});

/**
 * @OA\Get(
 *     path="/shelters/admins",
 *     tags={"shelters"},
 *     summary="Get all shelters with their admin names",
 *     @OA\Response(
 *         response=200,
 *         description="List of shelters with assigned admins"
 *     )
 * )
 */
Flight::route('GET /shelters/admins', function() {
    Flight::json(Flight::shelterService()->get_shelters_with_admins());
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
 *         description="Returns shelter matching the name"
 *     )
 * )
 */
Flight::route('GET /shelters/name/@name', function($name) {
    Flight::json(Flight::shelterService()->get_shelter_by_name($name));
});

/**
 * @OA\Post(
 *     path="/shelters",
 *     tags={"shelters"},
 *     summary="Create a new shelter",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "location", "admin_id"},
 *             @OA\Property(property="name", type="string", example="Happy Paws Shelter"),
 *             @OA\Property(property="location", type="string", example="Sarajevo, Bosnia"),
 *             @OA\Property(property="admin_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Shelter created successfully"
 *     )
 * )
 */
Flight::route('POST /shelters', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::shelterService()->create_shelter($data));
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
 *     )
 * )
 */
Flight::route('PUT /shelters/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::shelterService()->update_shelter($id, $data));
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
 *     )
 * )
 */
Flight::route('DELETE /shelters/@id', function($id) {
    Flight::json(Flight::shelterService()->delete_shelter($id));
});
?>
