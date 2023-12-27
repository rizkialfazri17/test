<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteConfigurationModel extends Model
{
    protected $table            = 'tbl_website_configuration';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['category', 'name', 'title', 'description', 'value', 'type', 'status'];

    // get config value
    public function getValue($name = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('value');
        $builder->where('name', array($name));
        $query = $builder->get();
        $row = $query->getRow();

        if (isset($row)) {
            return $row->value;
        } else {
            return false;
        }
    }
}
