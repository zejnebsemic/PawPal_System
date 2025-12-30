<?php
/**
 * @OA\Info(
 *     title="PawPal API",
 *     description="PawPal System API for managing animal shelters, adoptions and user interactions.",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="zejneb.semic@stu.ibu.edu.ba",
 *         name="Zejneb Semić"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url="https://plankton-app-yu9b6.ondigitalocean.app",
 *     description="API server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Paste your JWT token here in the format: Bearer {token}"
 * )
 */
