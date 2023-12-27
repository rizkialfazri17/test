<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileAccountModel extends Model
{
    protected $table            = 'tbl_profile_account';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'photo', 'nik', 'full_name', 'place_of_birth', 'date_of_birth', 'address', 'state', 'city', 'country', 'zip_code', 'gender', 'phone_number', 'time_updated'];
}
