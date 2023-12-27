<?php

namespace App\Models;

use CodeIgniter\Model;

class PostCategoryModel extends Model
{
    protected $table            = 'tbl_post_category';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name'];
}
