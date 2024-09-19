<?php

namespace App\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Models\AuthModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    public function register(): \CodeIgniter\HTTP\ResponseInterface
    {
        $requestData = $this->request->getJSON();

        log_message('info', 'Incoming request data: ' . json_encode($requestData));

        // Access the data
        $email = $requestData->email ?? null;
        $password = $requestData->password ?? null;
        $phone = $requestData->phone ?? null;
        $firstName = $requestData->first_name ?? null;
        $lastName = $requestData->last_name ?? null;

        // Validation logic
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => [
                'rules' => 'required|valid_email|is_unique[auth.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'A valid email is required',
                    'is_unique' => 'This email is already registered'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 6 characters',
                    'max_length' => "12 characters",
                    'regex_match' => 'Password must contain at least one uppercase letter and one number'
                ],
            ],
            'phone' => [
                'rules' => 'required|regex_match[/^\+36\/70\/\d{3}-\d{4}$/]',
                'errors' => [
                    'required' => 'Phone number is required',
                    'regex_match' => 'Phone number format is invalid, expected +36/70/xxx-xxxx'
                ]
            ],
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        // Validate the data
        if (!$validation->run((array)$requestData)) {
            return $this->fail($validation->getErrors(), 400);
        }

        // Hash password securely
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into auth table
        $authModel = new AuthModel();
        $authData = [
            'email' => $email,
            'password' => $hashedPassword,
        ];

        $authId = $authModel->insert($authData);
        if (!$authId) {
            return $this->fail('Registration failed', 500);
        }

        // Insert into users table
        $userModel = new UserModel();
        $userData = [
            'auth_id' => $authId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
        ];

        if ($userModel->insert($userData)) {
            return $this->respondCreated(['message' => 'User registered successfully']);
        } else {
            return $this->fail('User information registration failed', 500);
        }
    }

    public function login(): \CodeIgniter\HTTP\ResponseInterface
    {
        // Ensure we have request data and handle potential errors
        $requestData = $this->request->getJSON();

        if (!$requestData || !isset($requestData->email) || !isset($requestData->password)) {
            return $this->fail('Email or password missing', 400);
        }

        $email = $requestData->email;
        $password = $requestData->password;

        // Fetch the user from the auth table by email
        $authModel = new AuthModel();
        $userAuth = $authModel->where('email', $email)->first();

        // Check if the user exists and the password is correct
        if ($userAuth && password_verify($password, $userAuth['password'])) {
            // Update the last_login field with the current timestamp
            $authModel->update($userAuth['id'], ['last_login' => date('Y-m-d H:i:s')]);

            // Generate the JWT token
            $token = JwtHelper::generateJwt($userAuth['id']);

            return $this->respond([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
            ]);
        } else {
            return $this->fail('Invalid email or password', 401);
        }
    }

}
