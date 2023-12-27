<?php

namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{
    protected $table            = 'tbl_logs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'action', 'description', 'url', 'level', 'ip_address', 'user_agent', 'date_time'];
}
