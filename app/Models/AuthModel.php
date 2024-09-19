<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'auth';  // Corresponding to 'auth' table
    protected $allowedFields = ['email', 'password', 'created_at', 'last_login'];
    protected $primaryKey = 'id';  // Ensure primary key is defined
}
