<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BaseController;

class Signin extends BaseController
{
    public function index()
    {
        // check jika user sudah login
        if (session()->get('logged_in') && session()->get('username')) {
            // maka akan di alihkan ke dashboard
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/dashboard'));
        }

        // require data
        $data['website_title'] = lang('Pages.signin.title');

        // load layout and get page
        return view('Backend/Signin', $data);
    }

    // Login Validation
    public function signin()
    {
        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            // create form rules validation
            $rules = [
                'username' => [
                    'label' => lang('Label.username_or_email'),
                    'rules' => 'required|regex_match[/^[a-zA-Z0-9@._"]+$/]',
                    'errors' => [
                        'required' => lang('Validation.required'),
                        'regex_match' => lang('Validation.regex_match')
                    ]
                ],
                'password' => [
                    'label' => lang('Label.password'),
                    'rules' => 'required|alpha_numeric',
                    'errors' => [
                        'required' => lang('Validation.required'),
                        'alpha_numeric' => lang('Validation.alpha_numeric')
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rules)) {
                // get value for method post
                $username = $this->request->getVar('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $password = $this->request->getVar('password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $remember_me = $this->request->getVar('remember_me', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // get account for model
                $getAccount = $this->admin->where('username', $username)->orWhere('email', $username)->first();
                if (isset($getAccount)) {
                    if (password_verify($password, $getAccount['password'])) {
                        if ($getAccount['status'] != 0) {
                            if ($getAccount['status'] == 1) {
                                // create session login
                                $sessionLogin = [
                                    'username'  => $getAccount['username'],
                                    'role'      => $getAccount['role'],
                                    'logged_in' => TRUE
                                ];
                                session()->set($sessionLogin);

                                // jika remember me di centang
                                if (isset($remember_me) || $remember_me == 'true' || $remember_me == true || $remember_me == 1 || $remember_me == '1') {
                                    $admin_session = [
                                        'logged_in' => session()->get('logged_in'),
                                        'username'  => session()->get('username'),
                                        'role'      => session()->get('role')
                                    ];

                                    $encrypt_cookie = AESHash('encrypt', json_encode($admin_session), getenv('encryption.key'));
                                    set_cookie('admin_auth', $encrypt_cookie, 3600 * 24 * 3); // expired 3 days cookie saved in browser
                                }

                                // update admin data
                                $adminData = [
                                    'is_login'          => 1,
                                    'last_login_ip'     => $this->request->getIPAddress(),
                                    'last_login_time'   => date('Y-m-d H:i:s')
                                ];
                                $execUpdateAdminData = $this->admin->set($adminData)->where('username', $getAccount['username'])->update();

                                if (isset($execUpdateAdminData)) {
                                    // create log data and inserting log
                                    $logData = [
                                        'username'      => $getAccount['username'],
                                        'action'        => 'LOGIN',
                                        'description'   => 'Have successfully sign in',
                                        'url'           => base_url('/' . $this->request->getLocale() . '/admin/signin'),
                                        'level'         => $getAccount['role'],
                                        'ip_address'    => $this->request->getIPAddress(),
                                        'user_agent'    => $this->request->getUserAgent()
                                    ];
                                    $insertingLogData = $this->log->insert($logData);

                                    // jika insert log berhasil di jalankan tanpa ada error
                                    if (isset($insertingLogData)) {
                                        $response['status'] = true;
                                        $response['message'] = lang('Validation.success_login');
                                    } else {
                                        $response['status'] = false;
                                        $response['message'] = lang('Validation.failed_insert_log');
                                    }
                                } else {
                                    $response['status'] = false;
                                    $response['message'] = lang('Validation.failed_update_data');
                                }
                            } else {
                                $response['status'] = false;
                                $response['message'] = lang('Validation.account_suspend');
                            }
                        } else {
                            $response['status'] = false;
                            $response['message'] = lang('Validation.account_deactive');
                        }
                    } else {
                        $response['status'] = false;
                        $response['message'] = lang('Validation.wrong_password');
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = lang('Validation.username_or_email_not_found');
                }
            } else {
                $response['status'] = false;
                $response['message'] = $this->validation->getErrors();
            }

            // ouput json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url('/' . $this->request->getLocale() . '/admin/signin'));
        }
    }
}
