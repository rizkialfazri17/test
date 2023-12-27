<?php

namespace App\Controllers\Backend\Superadmin;

use App\Controllers\Backend\BaseController;

class Logs extends BaseController
{
    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('manage_logs');

        // require data
        $data['website_title']          = 'Logs';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
        $data['getListUsername']        = $this->log->select('username')->distinct()->findAll();
        $data['getListIp']              = $this->log->select('ip_address')->distinct()->findAll();
        $data['getListAction']          = $this->log->select('action')->distinct()->findAll();

        // load layout and get page
        return view('Backend/Superadmin/Logs', $data);
    }

    // table
    public function table()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('manage_logs');

        // request from ajax
        if ($this->request->isAJAX()) {
            $request    = service('request');
            $postData   = $request->getPost();
            $dtpostData = $postData['data'];
            $response   = array();

            // Read value
            $draw               = $dtpostData['draw'];
            $start              = $dtpostData['start'];
            $rowperpage         = $dtpostData['length']; // Rows display per page
            $columnIndex        = $dtpostData['order'][0]['column']; // Column index
            $columnName         = $dtpostData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder    = $dtpostData['order'][0]['dir']; // asc or desc
            $searchValue        = $dtpostData['search']['value']; // Search value

            // Custom filter
            $searchByUsername   = $dtpostData['searchByUsername'];
            $searchByIp         = $dtpostData['searchByIp'];
            $searchByAction     = $dtpostData['searchByAction'];
            $searchByDate       = $dtpostData['searchByDate'];

            // Total number of records without filtering
            $totalRecords = $this->log->select('id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->log->select('id');
            // group start
            if ($searchValue != '' || $searchByUsername != '' || $searchByIp != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupStart();
            }
            // jika kolom search di isi
            if ($searchValue != '') {
                $searchQuery->orLike('description', $searchValue);
            }
            // jika filter by username
            if ($searchByUsername != '') {
                $searchQuery->groupStart();
                $searchQuery->where('username', $searchByUsername);
                $searchQuery->groupEnd();
            }
            // jika filter by ip address
            if ($searchByIp != '') {
                $searchQuery->groupStart();
                $searchQuery->where('ip_address', $searchByIp);
                $searchQuery->groupEnd();
            }
            // jika filter by action di pilih
            if ($searchByAction != '') {
                $searchQuery->groupStart();
                $searchQuery->where('action', $searchByAction);
                $searchQuery->groupEnd();
            }
            // jika filter by date di isi
            if ($searchByDate != '') {
                $startDate  = substr($searchByDate, 0, 10);
                $endDate    = substr($searchByDate, 14, 14);
                $endDates   = ($endDate > date("Y-m-d") ? date("Y-m-d") : $endDate);

                // query
                $searchQuery->groupStart();
                $searchQuery->where('date_time >=', $startDate . ' 00:00:00');
                $searchQuery->where('date_time <=', $endDates . ' 23:59:59');
                $searchQuery->groupEnd();
            }
            // group end
            if ($searchValue != '' || $searchByUsername != '' || $searchByIp != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->log->select('*');
            // group start
            if ($searchValue != '' || $searchByUsername != '' || $searchByIp != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupStart();
            }
            // jika kolom search di isi
            if ($searchValue != '') {
                $searchQuery->orLike('description', $searchValue);
            }
            // jika filter by username
            if ($searchByUsername != '') {
                $searchQuery->groupStart();
                $searchQuery->where('username', $searchByUsername);
                $searchQuery->groupEnd();
            }
            // jika filter by ip address
            if ($searchByIp != '') {
                $searchQuery->groupStart();
                $searchQuery->where('ip_address', $searchByIp);
                $searchQuery->groupEnd();
            }
            // jika filter by action di pilih
            if ($searchByAction != '') {
                $searchQuery->groupStart();
                $searchQuery->where('action', $searchByAction);
                $searchQuery->groupEnd();
            }
            // jika filter by date di isi
            if ($searchByDate != '') {
                $startDate  = substr($searchByDate, 0, 10);
                $endDate    = substr($searchByDate, 14, 14);
                $endDates   = ($endDate > date("Y-m-d") ? date("Y-m-d") : $endDate);

                // query
                $searchQuery->groupStart();
                $searchQuery->where('date_time >=', $startDate . ' 00:00:00');
                $searchQuery->where('date_time <=', $endDates . ' 23:59:59');
                $searchQuery->groupEnd();
            }
            // group end
            if ($searchValue != '' || $searchByUsername != '' || $searchByIp != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupEnd();
            }

            if ($rowperpage == -1) {
                $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($start);
            } else {
                $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);
            }

            $data = array();
            foreach ($records as $record) {
                $data[] = array(
                    "username"      => $record['username'],
                    "action"        => $record['action'],
                    "description"   => $record['description'],
                    "ip_address"    => $record['ip_address'],
                    "date_time"     => $record['date_time'],
                    "level"         => $record['level']
                );
            }

            // Response
            $response = array(
                "draw"                  => intval($draw),
                "iTotalRecords"         => $totalRecords,
                "iTotalDisplayRecords"  => $totalRecordwithFilter,
                "aaData"                => $data,
                "token"                 => csrf_hash()
            );

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
