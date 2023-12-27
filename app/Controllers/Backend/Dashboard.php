<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BaseController;

class Dashboard extends BaseController
{
    // index
    public function index()
    {
        // check jika user belum login
        if (is_null(session()->get('logged_in')) || empty(session()->get('logged_in')) || is_null(session()->get('username')) || empty(session()->get('username'))) {
            // maka redirect ke halaman login
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signin'));
        }

        // check admin account
        $checkAdminAccount = $this->admin->where('username', session()->get('username'))->where('status', 2)->first();
        // jika admin account tersuspend
        if (isset($checkAdminAccount)) {
            // maka redirect ke halaman logout
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signout'));
        }

        // check profile data account
        $checkProfileAccount = $this->profile_account->where('username', session()->get('username'))->first();
        // jika user / admin belum memberikan data profile
        //if (is_null($checkProfileAccount) || empty($checkProfileAccount)) {
        // maka redirect ke halaman setting account terlebih dahulu
        //    return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/account-settings'));
        //}

        $getVisitorsCountToday      = $this->visitors->getCountToday();
        $getVisitorsCountYesterday  = $this->visitors->getCountYesterday();
        $getVisitorsCountThisMonth  = $this->visitors->getCountThisMonth();
        $getVisitorsCountThisYear   = $this->visitors->getCountThisYear();

        $totalCountVisitorsTodayYesterday = ((isset($getVisitorsCountToday['visits']) ? $getVisitorsCountToday['visits'] : 0) + (isset($getVisitorsCountYesterday['visits']) ? $getVisitorsCountYesterday['visits'] : 0));
        if ($totalCountVisitorsTodayYesterday >= 1) {
            $getPercentVisitorsToday     = ((isset($getVisitorsCountToday['visits']) ? $getVisitorsCountToday['visits'] : 0) / $totalCountVisitorsTodayYesterday * 100);
            $getPercentVisitorsYesterday = ((isset($getVisitorsCountYesterday['visits']) ? $getVisitorsCountYesterday['visits'] : 0) / $totalCountVisitorsTodayYesterday * 100);
        } else {
            $getPercentVisitorsToday     = 0;
            $getPercentVisitorsYesterday = 0;
        }

        // get percent text visitors today
        if ($getPercentVisitorsToday > $getPercentVisitorsYesterday) {
            $calculatePercentVisitors = (round($getPercentVisitorsToday * 100) - round($getPercentVisitorsYesterday * 100));
            $textPercentVisitorsToday = '<span class="text-success small pt-1 fw-bold">' . convertingPercent($calculatePercentVisitors) . '%</span> <span class="text-muted small pt-2 ps-1">' . lang('Global.increase') . '</span>';
        } else if ($getPercentVisitorsToday < $getPercentVisitorsYesterday) {
            $calculatePercentVisitors = (round($getPercentVisitorsYesterday * 100) - round($getPercentVisitorsToday * 100));
            $textPercentVisitorsToday = '<span class="text-danger small pt-1 fw-bold">' . convertingPercent($calculatePercentVisitors) . '%</span> <span class="text-muted small pt-2 ps-1">' . lang('Global.decrease') . '</span>';
        } else {
            $textPercentVisitorsToday = '<span class="text-muted small pt-2 ps-1">' . lang('Global.balance') . '</span>';
        }

        $getChartDataToday      = $this->visitors->getChartDataToday();
        $getChartDataYesterday  = $this->visitors->getChartDataYesterday();
        $getChartDataThisMonth  = $this->visitors->getChartDataThisMonth();
        $getChartDataThisYear   = $this->visitors->getChartDataThisYear();

        // require data
        $data['website_title']          = lang('Pages.dashboard.title');
        // $data['DataAccount']            = $this->account->where('username', session()->get('username'))->first();
        $data['countVisitorsToday']     = isset($getVisitorsCountToday['visits']) ? $getVisitorsCountToday['visits'] : 0;
        $data['countVisitorsYesterday'] = isset($getVisitorsCountYesterday['visits']) ? $getVisitorsCountYesterday['visits'] : 0;
        $data['countVisitorsThisMonth'] = isset($getVisitorsCountThisMonth['visits']) ? $getVisitorsCountThisMonth['visits'] : 0;
        $data['countVisitorsThisYear']  = isset($getVisitorsCountThisYear['visits']) ? $getVisitorsCountThisYear['visits'] : 0;
        $data['statsVisitorsToday']     = $textPercentVisitorsToday;
        $data['chartDataToday']         = isset($getChartDataToday) ? $getChartDataToday : [];
        $data['chartDataYesterday']     = isset($getChartDataYesterday) ? $getChartDataYesterday : [];
        $data['chartDataThisMonth']     = isset($getChartDataThisMonth) ? $getChartDataThisMonth : [];
        $data['chartDataThisYear']      = isset($getChartDataThisYear) ? $getChartDataThisYear : [];

        // load layout and get page
        return view('Backend/Dashboard', $data);
    }

    // table visitors
    public function table_visitors()
    {
        // check jika user belum login
        if (is_null(session()->get('logged_in')) || empty(session()->get('logged_in')) || is_null(session()->get('username')) || empty(session()->get('username'))) {
            // maka redirect ke halaman login
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signin'));
        }

        // check admin account
        $checkAdminAccount = $this->admin->where('username', session()->get('username'))->where('status', 2)->first();
        // jika admin account tersuspend
        if (isset($checkAdminAccount)) {
            // maka redirect ke halaman logout
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signout'));
        }

        // check profile data account
        $checkProfileAccount = $this->profile_account->where('username', session()->get('username'))->first();
        // jika user / admin belum memberikan data profile
        //if (is_null($checkProfileAccount) || empty($checkProfileAccount)) {
        // maka redirect ke halaman setting account terlebih dahulu
        //    return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/account-settings'));
        //}

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

            // Total number of records without filtering
            $totalRecords = $this->visitors->select('visitor_id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->visitors->select('visitor_id');
            // jika kolom search di isi
            if ($searchValue != '') {
                $searchQuery->orLike('ip_address', $searchValue)->orLike('requested_url', $searchValue);
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->visitors->select('*');
            // jika kolom search di isi
            if ($searchValue != '') {
                $searchQuery->orLike('ip_address', $searchValue)->orLike('requested_url', $searchValue);
            }

            // jika menampilkan semua
            if ($rowperpage == -1) {
                $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($start);
            } else {
                $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);
            }

            $data = [];
            foreach ($records as $record) {
                $data[] = [
                    'no_of_visits'  => $record['no_of_visits'],
                    'ip_address'    => $record['ip_address'],
                    'user_agent'    => $record['user_agent'],
                    'requested_url' => $record['requested_url'],
                    'access_date'   => $record['access_date']
                ];
            }

            // Response
            $response = [
                'draw'                  => intval($draw),
                'iTotalRecords'         => $totalRecords,
                'iTotalDisplayRecords'  => $totalRecordwithFilter,
                'aaData'                => $data,
                'token'                 => csrf_hash()
            ];

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/dashboard'));
        }
    }
}
