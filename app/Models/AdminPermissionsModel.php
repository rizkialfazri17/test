<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminPermissionsModel extends Model
{
    protected $table            = 'tbl_admin_permissions';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'permissions'];
}
