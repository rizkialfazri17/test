<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BaseController;

class Signout extends BaseController
{
    public function index()
    {
        // check jika user belum login
        if (is_null(session()->get('logged_in')) || empty(session()->get('logged_in')) || is_null(session()->get('username')) || empty(session()->get('username'))) {
            // maka redirect ke halaman login
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signin'));
        }

        // update admin data
        $execUpdateAdminData = $this->admin->set(['is_login' => 0])->where('username', session()->get('username'))->update();

        if (isset($execUpdateAdminData)) {
            // clear cookie and session
            delete_cookie('admin_auth');
            session()->destroy();

            // create log data and inserting log
            $logData = [
                'username'      => session()->get('username'),
                'action'        => 'LOGOUT',
                'description'   => 'Have successfully sign out',
                'url'           => base_url('/' . $this->request->getLocale() . '/admin/signout'),
                'level'         => session()->get('role'),
                'ip_address'    => $this->request->getIPAddress(),
                'user_agent'    => $this->request->getUserAgent()
            ];
            $insertingLogData = $this->log->insert($logData);

            if (isset($insertingLogData)) {
                // jika insert log berhasil di jalankan tanpa ada error
                session()->setFlashdata('success_signout', lang('Validation.success_logout'));
            } else {
                session()->setFlashdata('failed_signout', lang('Validation.failed_insert_log'));
            }
        } else {
            session()->setFlashdata('failed_signout', lang('Validation.failed_update_data'));
        }

        // require data
        $data['website_title'] = lang('Pages.signout.title');

        // load layout and get page
        return view('Backend/Signin', $data);
    }
}
