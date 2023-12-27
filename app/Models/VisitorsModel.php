<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitorsModel extends Model
{
    protected $table            = 'tbl_visitors';
    protected $primaryKey       = 'visitor_id';
    protected $allowedFields    = ['no_of_visits', 'ip_address', 'requested_url', 'referer_page', 'page_name', 'user_agent', 'access_date'];

    // get count today
    function getCountToday()
    {
        $results = array();
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits FROM ' . $this->table . ' WHERE CURDATE() = DATE(access_date) LIMIT 1');

        if ($query->resultID->num_rows == 1) {
            $row = $query->getRow();
            $results['visits'] = $row->visits;
        }

        return $results;
    }

    // get count yesterday
    function getCountYesterday()
    {
        $results = array();
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits FROM ' . $this->table . ' WHERE DATE(access_date) = CURDATE() - INTERVAL 1 DAY LIMIT 1');

        if ($query->resultID->num_rows == 1) {
            $row = $query->getRow();
            $results['visits'] = $row->visits;
        }

        return $results;
    }

    // get count this month
    function getCountThisMonth()
    {
        $results = array();
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits FROM ' . $this->table . ' WHERE MONTH(access_date) = MONTH(CURRENT_DATE()) AND YEAR(access_date) = YEAR(CURRENT_DATE()) LIMIT 1');

        if ($query->resultID->num_rows == 1) {
            $row = $query->getRow();
            $results['visits'] = $row->visits;
        }

        return $results;
    }

    // get count this year
    function getCountThisYear()
    {
        $results = array();
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits FROM ' . $this->table . ' WHERE YEAR(access_date) = YEAR(CURRENT_DATE()) LIMIT 1');

        if ($query->resultID->num_rows == 1) {
            $row = $query->getRow();
            $results['visits'] = $row->visits;
        }

        return $results;
    }

    // get chart data today
    function getChartDataToday()
    {
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits, DATE_FORMAT(access_date, "%h %p") AS hour FROM ' . $this->table . ' WHERE CURDATE() = DATE(access_date)
                GROUP BY HOUR(access_date)');

        if ($query->resultID->num_rows > 0) {
            return $query->getResult();
        }

        return NULL;
    }

    // get chart data yesterday
    function getChartDataYesterday()
    {
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits, DATE_FORMAT(access_date, "%h %p") AS hour FROM ' . $this->table . ' WHERE DATE(access_date) = CURDATE() - INTERVAL 1 DAY
                GROUP BY HOUR(access_date)');

        if ($query->resultID->num_rows > 0) {
            return $query->getResult();
        }

        return NULL;
    }

    // get chart data this month
    function getChartDataThisMonth()
    {
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits, DATE_FORMAT(access_date, "%a %d") AS day FROM ' . $this->table . ' 
                WHERE MONTH(access_date) = MONTH(CURRENT_DATE()) AND YEAR(access_date) = YEAR(CURRENT_DATE()) GROUP BY DATE(access_date)');

        if ($query->resultID->num_rows > 0) {
            return $query->getResult();
        }

        return NULL;
    }

    // get chart data this year
    function getChartDataThisYear()
    {
        $query = $this->db->query('SELECT SUM(no_of_visits) as visits, DATE_FORMAT(access_date, "%M") AS month FROM ' . $this->table . ' 
                WHERE YEAR(access_date) = YEAR(CURRENT_DATE()) GROUP BY MONTH(access_date)');

        if ($query->resultID->num_rows > 0) {
            return $query->getResult();
        }

        return NULL;
    }
}
