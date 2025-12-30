<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
Flight::group('/auth', function() {
   /**
    * @OA\Post(
    *     path="/auth/register",
    *     summary="Register new user.",
    *     description="Add a new user to the database.",
    *     tags={"auth"},
    *     security={
    *         {"ApiKey": {}}
    *     },
    *     @OA\RequestBody(
    *         description="Add new user",
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 required={"password", "email"},
    *                 @OA\Property(
    *                     property="password",
    *                     type="string",
    *                     example="some_password",
    *                     description="User password"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                     example="demo@gmail.com",
    *                     description="User email"
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="User has been added."
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Internal server error."
    *     )
    * )
    */
   Flight::route("POST /register", function () {
       try {
          
           $data = json_decode(file_get_contents("php://input"), true);
           
           
           error_log("REGISTER - Decoded data: " . print_r($data, true));

           if (!$data) {
               Flight::json([
                   'success' => false,
                   'error' => 'Invalid request format. Expected JSON object.'
               ], 400);
               return;
           }

           $required = ['full_name', 'email', 'phone_number', 'password', 'address'];

           foreach ($required as $field) {
               if (!isset($data[$field])) {
                   Flight::json([
                       'success' => false,
                       'error' => "Missing field: $field"
                   ], 400);
                   return;
               }
           }

           
           $serviceData = [
               'email' => $data['email'],
               'password' => $data['password'],
               'full_name' => $data['full_name'],
               'phone_number' => $data['phone_number'],
               'address' => $data['address']
           ];

           $response = Flight::auth_service()->register($serviceData);
      
           if ($response['success']) {
               Flight::json([
                   'success' => true,
                   'message' => 'User registered successfully',
                   'data' => $response['data']
               ], 201);
           } else {
               Flight::json([
                   'success' => false,
                   'error' => $response['error']
               ], 400);
           }
       } catch (Exception $e) {
           error_log("Error in register route: " . $e->getMessage());
           error_log("Exception trace: " . $e->getTraceAsString());
           Flight::json([
               'success' => false,
               'error' => 'Internal server error: ' . $e->getMessage()
           ], 500);
       }
   });
   /**
    * @OA\Post(
    *      path="/auth/login",
    *      tags={"auth"},
    *      summary="Login to system using email and password",
    *      @OA\Response(
    *           response=200,
    *           description="Student data and JWT"
    *      ),
    *      @OA\RequestBody(
    *          description="Credentials",
    *          @OA\JsonContent(
    *              required={"email","password"},
    *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="Student email address"),
    *              @OA\Property(property="password", type="string", example="some_password", description="Student password")
    *          )
    *      )
    * )
    */
   Flight::route('POST /login', function() {
       try {
           
           $data = json_decode(file_get_contents("php://input"), true);
           
           
           error_log("LOGIN - Decoded data: " . print_r($data, true));

           if (!$data || !isset($data['email']) || !isset($data['password'])) {
               Flight::json([
                   'success' => false,
                   'error' => 'Invalid request format. Expected JSON object.'
               ], 400);
               return;
           }

           $email = $data['email'];
           $password = $data['password'];

           
           $serviceData = [
               'email' => $email,
               'password' => $password
           ];

           $response = Flight::auth_service()->login($serviceData);
      
           if ($response['success']) {
               Flight::json([
                   'success' => true,
                   'message' => 'User logged in successfully',
                   'data' => $response['data']
               ], 200);
           } else {
               
               $statusCode = 400; 
               if (isset($response['error_type']) && $response['error_type'] === 'validation') {
                   $statusCode = 400;
               } else if (strpos($response['error'], 'Invalid username or password') !== false || 
                         strpos($response['error'], 'Invalid email or password') !== false) {
                   $statusCode = 401; 
               }
               Flight::json([
                   'success' => false,
                   'error' => $response['error']
               ], $statusCode);
           }
       } catch (Exception $e) {
           error_log("Error in login route: " . $e->getMessage());
           error_log("Exception trace: " . $e->getTraceAsString());
           Flight::json([
               'success' => false,
               'error' => 'Internal server error: ' . $e->getMessage()
           ], 500);
       }
   });
});
?>
