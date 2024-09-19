<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';  // Corresponding to 'users' table
    protected $allowedFields = ['auth_id', 'phone', 'first_name', 'last_name'];
    protected $primaryKey = 'id';  // Ensure primary key is defined
}
