<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminsModel extends Model
{
    protected $table            = 'tbl_admins';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password', 'email', 'role', 'status', 'is_login', 'created_time', 'last_login_time', 'last_login_ip', 'updated_time'];
}
