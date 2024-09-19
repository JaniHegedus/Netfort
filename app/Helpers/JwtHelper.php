<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function generateJwt($userId)
    {
        $key = getenv('JWT_SECRET_KEY'); // Get the secret key from the environment
        $payload = [
            'iss' => "your_issuer", // Issuer
            'iat' => time(),         // Issued at: time when the token was generated
            'exp' => time() + 3600,  // Expiration time (1 hour)
            'user_id' => $userId,
        ];
        return JWT::encode($payload, $key, 'HS256'); // Specify the algorithm here
    }

    public static function validateJwt($token)
    {
        $key = getenv('JWT_SECRET_KEY');
        try {
            return JWT::decode($token, new Key($key, 'HS256')); // Use Key class for the key
        } catch (\Exception $e) {
            error_log($e->getMessage()); // Log the exception message for debugging
            return null; // Invalid token
        }
    }


}
