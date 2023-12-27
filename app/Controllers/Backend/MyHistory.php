<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BaseController;

class MyHistory extends BaseController
{
    // index
    public function index()
    {
        // check jika user belum login
        if (is_null(session()->get('logged_in')) || empty(session()->get('logged_in')) || is_null(session()->get('username')) || empty(session()->get('username'))) {
            // maka redirect ke halaman login
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signin'));
        }

        $checkAdminAccount = $this->admin->where('username', session()->get('username'))->where('status', 2)->first();
        // jika admin account tersuspend
        if (isset($checkAdminAccount)) {
            // maka redirect ke halaman logout
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signout'));
        }

        // require data
        $data['website_title']          = lang('Pages.my_history.title');
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();

        $data['getListAction']          = $this->log->select('action')->where('username', session()->get('username'))->distinct()->findAll();
        $data['getCountAll']            = $this->log->select('id')->where('username', session()->get('username'))->countAllResults();
        $data['getCountToday']          = $this->log->select('id')->where('username', session()->get('username'))->where('date_time >=', date('Y-m-d'))->countAllResults();
        $data['getCountYesterday']      = $this->log->select('id')->where('username', session()->get('username'))->where('date_time >=', date('Y-m-d', strtotime("-1 days")))->where('date_time <', date('Y-m-d'))->countAllResults();
        $data['getCountThisWeek']       = $this->log->select('id')->where('username', session()->get('username'))->where('date_time >=', date('Y-m-d', strtotime("-7 days")))->countAllResults();
        $data['getCountThisMonth']      = $this->log->select('id')->where('username', session()->get('username'))->where('date_time >=', date('Y-m'))->countAllResults();

        // load layout and get page
        return view('Backend/MyHistory', $data);
    }

    // table
    public function table()
    {
        // check jika user belum login
        if (is_null(session()->get('logged_in')) || empty(session()->get('logged_in')) || is_null(session()->get('username')) || empty(session()->get('username'))) {
            // maka redirect ke halaman login
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signin'));
        }

        $checkAdminAccount = $this->admin->where('username', session()->get('username'))->where('status', 2)->first();
        // jika admin account tersuspend
        if (isset($checkAdminAccount)) {
            // maka redirect ke halaman logout
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signout'));
        }

        // request from ajax
        if ($this->request->isAJAX()) {
            $request    = service('request');
            $postData   = $request->getPost();
            $dtpostData = $postData['data'];
            $response   = [];

            // Read value
            $draw               = $dtpostData['draw'];
            $start              = $dtpostData['start'];
            $rowperpage         = $dtpostData['length']; // Rows display per page
            $columnIndex        = $dtpostData['order'][0]['column']; // Column index
            $columnName         = $dtpostData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder    = $dtpostData['order'][0]['dir']; // asc or desc
            $searchValue        = $dtpostData['search']['value']; // Search value

            // Custom filter
            $searchByAction     = $dtpostData['searchByAction'];
            $searchByDate       = $dtpostData['searchByDate'];

            // Total number of records without filtering
            $totalRecords = $this->log->select('id')->where('username', session()->get('username'))->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->log->select('id')->where('username', session()->get('username'));
            // group start
            if ($searchValue != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupStart();
            }
            // jika kolom search di isi
            if ($searchValue != '') {
                $searchQuery->orLike('description', $searchValue);
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
            if ($searchValue != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->log->select('*')->where('username', session()->get('username'));
            // group start
            if ($searchValue != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupStart();
            }
            // jika kolom search di isi
            if ($searchValue != '') {
                $searchQuery->orLike('description', $searchValue);
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
            if ($searchValue != '' || $searchByAction != '' || $searchByDate != '') {
                $searchQuery->groupEnd();
            }

            if ($rowperpage == -1) {
                $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($start);
            } else {
                $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);
            }

            $data = [];
            foreach ($records as $record) {
                $data[] = [
                    "action"        => $record['action'],
                    "description"   => $record['description'],
                    "ip_address"    => $record['ip_address'],
                    "date_time"     => $record['date_time'],
                    "level"         => $record['level']
                ];
            }

            // Response
            $response = [
                "draw"                  => intval($draw),
                "iTotalRecords"         => $totalRecords,
                "iTotalDisplayRecords"  => $totalRecordwithFilter,
                "aaData"                => $data,
                "token"                 => csrf_hash()
            ];

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
