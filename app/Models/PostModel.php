<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'tbl_post';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['category', 'slug', 'title', 'description', 'images', 'content', 'author', 'is_view', 'viewers', 'status', 'created_time', 'updated_time'];

    // get total count viewers
    function getTotalCountViewers()
    {
        $results = [];
        $query = $this->db->query('SELECT SUM(viewers) as viewers FROM ' . $this->table . ' LIMIT 1');

        if ($query->resultID->num_rows == 1) {
            $row = $query->getRow();
            $results['viewers'] = $row->viewers;
        }

        return $results;
    }
}
