<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BaseController;

class MyProfile extends BaseController
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

        // require data
        $data['WebsiteTitle']   = lang('Pages.my_profile.title');
        $data['DataAccount']    = $this->admin->where('username', session()->get('username'))->first();
        $data['DataProfile']    = $this->profile_account->where('username', session()->get('username'))->first();

        // load layout and get page
        return view('Backend/MyProfile', $data);
    }

    // update
    public function update()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            // create form rules validation
            $rulesValidation = [
                'photo_profile' => [
                    'rules'     => 'mime_in[photo_profile,image/jpg,image/jpeg,image/png]|max_dims[photo_profile,120,120]|max_size[photo_profile,128]',
                    'errors'    => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 120px & height: 120px!',
                        'max_size'      => 'Maximum size image cannot be more than 128 KB!'
                    ]
                ],
                'name' => [
                    'rules'     => 'required|alpha_space|min_length[3]|max_length[48]',
                    'errors'    => [
                        'required'      => 'Please fill in the name column first!',
                        'alpha_space'   => 'The name field may only contain alpha characters and space!',
                        'min_length'    => 'The name field must be at least 3 characters in length!',
                        'max_length'    => 'The name field cannot exceed 48 characters in length!'
                    ]
                ],
                'email' => [
                    'rules'     => 'required|valid_email',
                    'errors'    => [
                        'required'      => 'Please fill in the email column first!',
                        'valid_email'   => 'Please enter a valid email!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // check account from session
                $account = $this->admin->where('username', session()->get('username'))->first();
                // jika account ditemukan dan sudah login
                if (isset($account)) {
                    // get value for method post
                    $postName   = $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postEmail  = $this->request->getPost('email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postFile   = $this->request->getFile('photo_profile');
                    $checkFile  = $postFile->getFilename();

                    // jika ada file yang di upload
                    if (!empty($checkFile)) {
                        $fileName = 'avatar_' . $account['username'] . '_' . $postFile->getRandomName();

                        // uploaded file
                        $this->image->withFile($postFile)->fit(120, 120, 'center')->save(FCPATH . '/assets/images/avatar/' . $fileName); // execute image imanipulation
                        $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                        $updateMyProfileData['avatar'] = $fileName;
                    }

                    // check name jika di ubah
                    if ($account['name'] != $postName) {
                        $updateMyProfileData['name'] = $postName;
                    }

                    // check email jika di ubah
                    if ($account['email'] != $postEmail) {
                        $updateMyProfileData['email'] = $postEmail;
                    }

                    if (isset($updateMyProfileData)) {
                        // create log data and inserting log
                        $insertLogData = [
                            'username'      => $account['username'],
                            'action'        => 'UPDATE PROFILE',
                            'description'   => 'Have successfully updated profile',
                            'url'           => base_url('/admin/my-profile'),
                            'level'         => session()->get('role'),
                            'ip_address'    => $this->request->getIPAddress(),
                            'user_agent'    => $this->request->getUserAgent()
                        ];
                        $postFile = $this->log->insert($insertLogData);

                        if (isset($postFile)) {
                            // update admin data
                            $updateMyProfileData['updated_time'] = date("Y-m-d H:i:s");
                            $execUpdateAccount = $this->admin->set($updateMyProfileData)->where('username', $account['username'])->update();

                            if (isset($execUpdateAccount)) {
                                $response['status'] = true;
                                $response['message'] = "Your profile has been successfully updated!";
                            } else {
                                $response['status'] = false;
                                $response['message'] = 'Failed to update data, Please contact Developer!';
                            }
                        } else {
                            $response['status'] = false;
                            $response['message'] = 'Failed to insert log, Please contact Developer!';
                        }
                    } else {
                        $response['status'] = false;
                        $response['message'] = "No data has changed!";
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = "Account not found or You have not logged in, please sign in first!";
                }
            } else {
                $response['status'] = false;
                $response['message'] = $this->validation->getErrors();
            }

            // ouput json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }

    // Change Password
    public function change_password()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            // create form rules validation
            $rulesValidation = [
                'current_password' => [
                    'rules'     => 'required|alpha_numeric',
                    'errors'    => [
                        'required'      => 'Please fill in the column first!',
                        'alpha_numeric' => 'Current password can only contain letters & numbers!'
                    ]
                ],
                'new_password' => [
                    'rules'     => 'required|alpha_numeric|min_length[6]|max_length[24]',
                    'errors'    => [
                        'required'      => 'Please fill in the column first!',
                        'alpha_numeric' => 'New password can only contain letters & numbers!',
                        'min_length'    => 'The new password must consist of 6 characters or more!',
                        'max_length'    => 'New password must not exceed 24 characters!'
                    ]
                ],
                'confirm_password' => [
                    'rules'     => 'required|matches[new_password]',
                    'errors'    => [
                        'required'      => 'Please fill in the column first!',
                        'matches'       => 'Confirm password is not the same as new password!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // check account from session
                $account = $this->admin->where('username', session()->get('username'))->first();
                // jika account ditemukan dan sudah login
                if (isset($account)) {
                    // get value for method post
                    $postCurrentPassword = $this->request->getPost('current_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postNewPassword     = $this->request->getPost('new_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    // check current password
                    if (password_verify($postCurrentPassword, $account['password'])) {
                        // create log data and inserting log
                        $insertLogData = [
                            'username'      => $account['username'],
                            'action'        => 'CHANGE PASSWORD',
                            'description'   => 'Have successfully changed the password',
                            'url'           => base_url('/admin/my-profile'),
                            'level'         => session()->get('role'),
                            'ip_address'    => $this->request->getIPAddress(),
                            'user_agent'    => $this->request->getUserAgent()
                        ];
                        $execInsertLog = $this->log->insert($insertLogData);

                        if (isset($execInsertLog)) {
                            // update admin data
                            $updatePasswordData['password']     = password_hash($postNewPassword, PASSWORD_DEFAULT);
                            $updatePasswordData['updated_time'] = date("Y-m-d H:i:s");

                            $execChangePassword = $this->admin->set($updatePasswordData)->where('username', $account['username'])->update();

                            if (isset($execChangePassword)) {
                                $response['status'] = true;
                                $response['message'] = "You have successfully changed your password, please sign in again!";
                            } else {
                                $response['status'] = false;
                                $response['message'] = 'Failed to update password, Please contact Developer!';
                            }
                        } else {
                            $response['status'] = false;
                            $response['message'] = 'Failed to insert log, Please contact Developer!';
                        }
                    } else {
                        $response['status'] = false;
                        $response['message'] = 'The current password you entered is incorrect!';
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = "Account not found or You have not logged in, please sign in first!";
                }
            } else {
                $response['status'] = false;
                $response['message'] = $this->validation->getErrors();
            }

            // ouput json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
