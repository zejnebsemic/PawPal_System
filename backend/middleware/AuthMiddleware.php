<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthMiddleware {
   
   
   private function getAuthToken() {
      
       $headers = [];
       
       
       if (function_exists('getallheaders')) {
           $headers = getallheaders();
           if (!is_array($headers)) {
               $headers = [];
           }
       }
       
       
       $authHeader =
           $headers['Authorization']
           ?? $headers['authorization']
           ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? null);
       
       
       error_log("AuthMiddleware::getAuthToken - Authorization header found: " . ($authHeader ? "YES" : "NO"));
       if ($authHeader) {
           error_log("AuthMiddleware::getAuthToken - Header value (first 50 chars): " . substr($authHeader, 0, 50) . "...");
       } else {
           
           error_log("AuthMiddleware::getAuthToken - Available headers from getallheaders(): " . print_r($headers, true));
           error_log("AuthMiddleware::getAuthToken - HTTP_AUTHORIZATION in \$_SERVER: " . (isset($_SERVER['HTTP_AUTHORIZATION']) ? "YES (value: " . substr($_SERVER['HTTP_AUTHORIZATION'], 0, 30) . "...)" : "NO"));
       }
       
       if (!$authHeader) {
           error_log("AuthMiddleware::getAuthToken - No Authorization header found");
           return null;
       }
       
      
       if (stripos($authHeader, 'Bearer ') === 0) {
           $token = trim(substr($authHeader, 7));
       } else {
           $token = trim($authHeader);
       }
       
       if (!$token) {
           error_log("AuthMiddleware::getAuthToken - Token is empty after extraction");
           return null;
       }
       
       error_log("AuthMiddleware::getAuthToken - Extracted token (first 30 chars): " . substr($token, 0, 30));
       error_log("AuthMiddleware::getAuthToken - Token length: " . strlen($token));
       
       return $token;
   }
   
   public function verifyToken($token = null){
      
       if (!$token) {
           
           $headers = [];
           
           if (function_exists('getallheaders')) {
               $headers = getallheaders();
           }
           
           $authHeader =
               $headers['Authorization']
               ?? $headers['authorization']
               ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? null);
           
           if (!$authHeader) {
               error_log("AuthMiddleware::verifyToken - No Authorization header found");
               Flight::halt(401, 'Authentication required');
           }
           
           if (stripos($authHeader, 'Bearer ') === 0) {
               $token = trim(substr($authHeader, 7));
           } else {
               $token = trim($authHeader);
           }
           
           if (!$token) {
               error_log("AuthMiddleware::verifyToken - Token is empty after extraction");
               Flight::halt(401, 'Invalid token');
           }
       }
       
       error_log("AuthMiddleware::verifyToken called");
       error_log("AuthMiddleware::verifyToken - Token received: " . ($token ? substr($token, 0, 30) . "..." : "NULL/EMPTY"));
       error_log("AuthMiddleware::verifyToken - Token length: " . ($token ? strlen($token) : 0));
       
       if(!$token || empty(trim($token))) {
           error_log("AuthMiddleware::verifyToken - Token is empty or null, halting with 401");
           Flight::halt(401, "Missing authentication header");
       }
       
       try {
           $jwt_secret = Config::JWT_SECRET();
           error_log("AuthMiddleware::verifyToken - JWT secret (first 10 chars): " . substr($jwt_secret, 0, 10) . "...");
           error_log("AuthMiddleware::verifyToken - JWT secret length: " . strlen($jwt_secret));
           
           
           if (empty($jwt_secret)) {
               error_log("AuthMiddleware::verifyToken - ERROR: JWT secret is empty!");
               Flight::halt(500, "Server configuration error");
           }
           
           
           error_log("JWT token received: " . substr($token, 0, 30));
           error_log("JWT token full length: " . strlen($token));
           
           
           error_log("AuthMiddleware::verifyToken - Verifying JWT secret consistency:");
           error_log("  - Secret used for decode: " . substr($jwt_secret, 0, 20) . "...");
           error_log("  - Secret length: " . strlen($jwt_secret));
           error_log("  - Algorithm: HS256");
           
           
           error_log("AuthMiddleware::verifyToken - Attempting JWT decode...");
           $decoded = JWT::decode(
               $token,
               new Key($jwt_secret, 'HS256')
           );
           
           error_log("AuthMiddleware::verifyToken - JWT decoded successfully!");
           
           
           error_log("JWT decoded user: " . json_encode($decoded->user ?? null));
           
           
           if (!isset($decoded->user)) {
               error_log("AuthMiddleware::verifyToken - ERROR: Decoded token missing 'user' property");
               error_log("AuthMiddleware::verifyToken - Decoded token keys: " . print_r(array_keys((array)$decoded), true));
               Flight::halt(401, "Invalid token structure");
           }
           
           error_log("AuthMiddleware::verifyToken - Decoded user ID: " . ($decoded->user->user_id ?? 'N/A'));
           error_log("AuthMiddleware::verifyToken - Decoded user role: " . ($decoded->user->role ?? 'N/A'));
           
           Flight::set('user', $decoded->user);
           Flight::set('jwt_token', $token);
           return TRUE;
       } catch (\Firebase\JWT\ExpiredException $e) {
           error_log("AuthMiddleware::verifyToken - JWT expired: " . $e->getMessage());
           Flight::halt(401, "Token expired");
       } catch (\Firebase\JWT\SignatureInvalidException $e) {
           error_log("AuthMiddleware::verifyToken - JWT signature invalid: " . $e->getMessage());
           error_log("AuthMiddleware::verifyToken - This usually means JWT secret mismatch!");
           error_log("AuthMiddleware::verifyToken - Token (first 20 chars): " . substr($token, 0, 20));
           error_log("AuthMiddleware::verifyToken - Secret (first 10 chars): " . substr($jwt_secret, 0, 10));
           Flight::halt(401, "Invalid token signature");
       } catch (\Exception $e) {
           error_log("AuthMiddleware::verifyToken - JWT decode failed: " . $e->getMessage());
            Flight::halt(401, "Invalid token");
       }
   }
   public function authorizeRole($requiredRole) {
       
       $user = Flight::get('user');
       if (!$user || !isset($user->role)) {
           
           $this->verifyToken();
           $user = Flight::get('user');
           if (!$user || !isset($user->role)) {
               Flight::halt(401, 'Authentication required');
           }
       }
       if ($user->role !== $requiredRole) {
           Flight::halt(403, 'Access denied: insufficient privileges');
       }
   }
   public function authorizeRoles($roles) {
      
       $user = Flight::get('user');
       if (!$user || !isset($user->role)) {
          
           $this->verifyToken();
           $user = Flight::get('user');
           if (!$user || !isset($user->role)) {
               Flight::halt(401, 'Authentication required');
           }
       }
       if (!in_array($user->role, $roles)) {
           Flight::halt(403, 'Forbidden: role not allowed');
       }
   }
   function authorizePermission($permission) {
       $user = Flight::get('user');
       if (!$user || !isset($user->permissions)) {
           Flight::halt(401, 'Authentication required');
       }
       if (!in_array($permission, $user->permissions)) {
           Flight::halt(403, 'Access denied: permission missing');
       }
   }   
}

