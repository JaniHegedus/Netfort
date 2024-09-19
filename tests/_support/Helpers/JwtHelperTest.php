<?php

use App\Helpers\JwtHelper;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

class JwtHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        putenv('JWT_SECRET_KEY=bdcyodks9EnyiwKRowsj0qMCpyJbkykBKfxYV9WkHk0=');
    }

    public function testGenerateJwt()
    {
        $jwtHelper = new JwtHelper();
        $token = $jwtHelper->generateJwt('1');
        $this->assertNotEmpty($token, "JWT should not be empty");
    }

    public function testValidateJwt()
    {
        $jwtHelper = new JwtHelper();
        $token = $jwtHelper->generateJwt('1');
        $decoded = $jwtHelper->validateJwt($token);
        $this->assertNotNull($decoded, "Decoded JWT should not be null");
    }

    public function testValidateExpiredJwt()
    {
        $jwtHelper = new JwtHelper();
        // Create a payload with an expiration time in the past
        $expiredPayload = [
            'iss' => "your_issuer",
            'iat' => time() - 3600,  // Issued at: 1 hour ago
            'exp' => time() - 1800,   // Expiration time: 30 minutes ago
            'user_id' => '1',
        ];

        $expiredToken = JWT::encode($expiredPayload, getenv('JWT_SECRET_KEY'), 'HS256');
        $decoded = $jwtHelper->validateJwt($expiredToken);
        $this->assertNull($decoded, "Expired token should be invalid");
    }


}
