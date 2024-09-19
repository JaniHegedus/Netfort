<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Helpers\JwtHelper; // Ensure the helper is imported
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get the Authorization header
        $authHeader = $request->getHeader('Authorization');

        // Check if the token exists and starts with 'Bearer'
        if (!$authHeader || strpos($authHeader->getValue(), 'Bearer ') !== 0) {
            return Services::response()
                ->setStatusCode(401)
                ->setBody('Unauthorized: Missing or invalid Authorization header');
        }

        // Extract the token from the header
        $token = substr($authHeader->getValue(), 7);

        // Validate the token using JwtHelper
        $decodedToken = JwtHelper::validateJwt($token);

        if (!$decodedToken) {
            return Services::response()
                ->setStatusCode(401)
                ->setBody('Unauthorized: Invalid or expired token');
        }

        // Token is valid, proceed with request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // You can add any post-request logic here if necessary
    }

    // Use JwtHelper's validation method
    private function validateJwt($token)
    {
        return JwtHelper::validateJwt($token);
    }
}
