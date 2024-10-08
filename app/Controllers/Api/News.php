<?php

namespace App\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Models\NewsModel;
use CodeIgniter\RESTful\ResourceController;

class News extends ResourceController
{
    public function upload()
    {
        // Get the JSON payload from the request
        $requestData = $this->request->getJSON();
        log_message('info', 'Incoming request data: ' . json_encode($requestData));

        // Validate JWT token
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->failUnauthorized('Token is required');
        }

        // Extract token from the Authorization header
        $authHeaderValue = $authHeader->getValue();
        if (strpos($authHeaderValue, 'Bearer ') !== 0) {
            return $this->failUnauthorized('Malformed Authorization header');
        }

        $token = str_replace('Bearer ', '', $authHeaderValue);

        // Validate the token
        $decoded = JwtHelper::validateJwt($token); // Validate the token

        if (!$decoded) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        // Access the data safely
        $title = $requestData->title ?? null;
        $intro = $requestData->intro ?? null;
        $body = $requestData->body ?? null;
        $authorId = $requestData->author_id ?? $decoded->user_id; // Use author ID from the token if not provided

        // Validation
        if (empty($title) || empty($body)) {
            return $this->fail('Title and body are required', 400);
        }

        // Insert into the database
        $newsModel = new NewsModel();
        $data = [
            'title' => $title,
            'introduction' => $intro,
            'body' => $body,
            'author_id' => $authorId,
        ];

        if ($newsModel->insert($data)) {
            return $this->respondCreated(['message' => 'News uploaded successfully']);
        } else {
            return $this->fail('Upload failed', 500);
        }
    }
    public function getNews()
    {
        // Retrieve all news from the database
        $newsModel = new NewsModel();
        $newsData = $newsModel->findAll(); // Fetch all news entries

        // Check if any news entries were found
        if (!$newsData) {
            return $this->failNotFound('No news found');
        }

        // Return the news data as a response
        return $this->respond($newsData);
    }

}
