<?php

namespace App\Controllers\Backend\Superadmin;

use App\Controllers\Backend\BaseController;

class ManageAdmins extends BaseController
{
    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('manage_admins');

        // sending data to view
        $data['website_title']              = 'List Admin';
        $data['getDataAdminSession']        = $this->admin->where('username', session()->get('username'))->first();
        $data['getPermissionsAdminSession'] = $this->admin_permissions->where('username', session()->get('username'))->first();
        $data['countTotalAdmin']            = $this->admin->countAll();
        $data['countTotalAdminActive']      = $this->admin->where('status', 1)->countAllResults();
        $data['countTotalAdminSuspended']   = $this->admin->where('status', 2)->countAllResults();
        $data['countTotalAdminIsLogin']     = $this->admin->where('is_login', 1)->countAllResults();
        $data['getListRole']                = $this->admin->select('role')->distinct()->findAll();
        $data['getListStatus']              = $this->admin->select('status')->distinct()->findAll();
        $data['getIsLogin']                 = $this->admin->select('is_login')->distinct()->findAll();

        // load layout and get the page
        return view('Backend/Superadmin/ManageAdmins/Index', $data);
    }

    // table list
    public function table()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('manage_admins');

        // request from ajax
        if ($this->request->isAJAX()) {
            $request    = service('request');
            $postData   = $request->getPost(); // $postData = $this->request->getPost();
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
            $searchByRole       = $dtpostData['searchByRole'];
            $searchByStatus     = $dtpostData['searchByStatus'];
            $searchByIsLogin    = $dtpostData['searchByIsLogin'];

            // Total number of records without filtering
            $totalRecords = $this->admin->select('id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->admin->select('id');
            // group start
            if ($searchValue != '' || $searchByRole != '' || $searchByStatus != '' || $searchByIsLogin != '') {
                $searchQuery->groupStart();
            }
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                $searchQuery->orLike('username', $searchValue)->orLike('name', $searchValue);
            }
            // jika ada yang di filter melalui costume filter
            if ($searchByRole != '') {
                $searchQuery->groupStart();
                $searchQuery->where('role', $searchByRole);
                $searchQuery->groupEnd();
            }
            if ($searchByStatus != '') {
                $searchQuery->groupStart();
                $searchQuery->where('status', $searchByStatus);
                $searchQuery->groupEnd();
            }
            if ($searchByIsLogin != '') {
                $searchQuery->groupStart();
                $searchQuery->where('is_login', $searchByIsLogin);
                $searchQuery->groupEnd();
            }
            // group end
            if ($searchValue != '' || $searchByRole != '' || $searchByStatus != '' || $searchByIsLogin != '') {
                $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->admin->select('*');
            // group start
            if ($searchValue != '' || $searchByRole != '' || $searchByStatus != '' || $searchByIsLogin != '') {
                $searchQuery->groupStart();
            }
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                $searchQuery->orLike('username', $searchValue)->orLike('name', $searchValue);
            }
            // jika ada yang di filter melalui costume filter
            if ($searchByRole != '') {
                $searchQuery->groupStart();
                $searchQuery->where('role', $searchByRole);
                $searchQuery->groupEnd();
            }
            if ($searchByStatus != '') {
                $searchQuery->groupStart();
                $searchQuery->where('status', $searchByStatus);
                $searchQuery->groupEnd();
            }
            if ($searchByIsLogin != '') {
                $searchQuery->groupStart();
                $searchQuery->where('is_login', $searchByIsLogin);
                $searchQuery->groupEnd();
            }
            // group end
            if ($searchValue != '' || $searchByRole != '' || $searchByStatus != '' || $searchByIsLogin != '') {
                $searchQuery->groupEnd();
            }
            $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);

            $data = array();
            foreach ($records as $record) {
                $getAccountPermissions = $this->admin_permissions->where('username', session()->get('username'))->first();
                if (session()->get('role') >= 3 || isset($getAccountPermissions)) {
                    $getPermissionEditAdmins = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'edit_admins') : FALSE);
                    $getPermissionDeleteAdmins = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'delete_admins') : FALSE);
                    $getPermissionEditAdminPermissions = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'edit_admin_permissions') : FALSE);
                }

                $data[] = array(
                    "username"          => $record['username'],
                    "name"              => $record['name'],
                    "role"              => $record['role'],
                    "status"            => $record['status'],
                    "is_login"          => $record['is_login'],
                    "last_login_time"   => (isset($record['last_login_time']) ? $record['last_login_time'] : 'Not Yet Logged In'),
                    "options"           => '<a href="javascript:void(0);" id="' . $record['username'] . '" data-bs-toggle="modal" data-bs-target="#view_detail_admin" type="button" class="btn btn-primary btn-xs shadow-none button_view_detail_admin" title="View Detail"><i class="bi bi-eye"></i></a> '
                        . (session()->get('role') >= 3 || $getPermissionEditAdmins === TRUE ? '<a href="' . base_url('/admin/manage-admins/edit/' . $record['username']) . '" type="button" class="btn btn-success btn-xs shadow-none" title="Edit Account"><i class="bi bi-pencil-square"></i></a> ' : '')
                        . (session()->get('role') >= 3 || $getPermissionEditAdminPermissions === TRUE ? '<a href="' . base_url('/admin/manage-admins/edit-permissions/' . $record['username']) . '" type="button" class="btn btn-warning btn-xs shadow-none" title="Edit Permissions"><i class="bi bi-shield-lock"></i></a> ' : '')
                        . (session()->get('role') >= 3 || $getPermissionDeleteAdmins === TRUE ? '<a href="javascript:void(0);" id="' . $record['username'] . '" type="button" class="btn btn-danger btn-xs shadow-none button_delete_admin" title="Delete"><i class="bi bi-trash"></i></a>' : '')
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

    // view detail
    public function view($username = null)
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('manage_admins');

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => []];

            // get admin account by uri parameter username
            $getAdminData = $this->admin->where('username', $username)->first();
            // check admin account
            if (isset($getAdminData)) {
                if (!empty($getAdminData['avatar'])) {
                    $getAvatar = (FCPATH . '/assets/images/avatar/' . $getAdminData['avatar']);

                    if (file_exists($getAvatar)) {
                        $avatar = '<img src="' . base_url('/assets/images/avatar/' . $getAdminData['avatar']) . '" class="rounded-circle" alt="Profile ' . $getAdminData['name'] . '" width="80" />';
                    } else {
                        $avatar = '<img src="' . base_url('/assets/images/avatar/default.png') . '" class="rounded-circle" alt="Profile ' . $getAdminData['name'] . '" width="80" />';
                    }
                } else {
                    $avatar = '<img src="' . base_url('/assets/images/avatar/default.png') . '" class="rounded-circle" alt="Profile ' . $getAdminData['name'] . '" width="80" />';
                }

                $created_time       = date('l, d F Y - h:i:s A', strtotime($getAdminData['created_time'])) . ' [' . getTimeAgo(date('d-m-Y H:i:s', strtotime($getAdminData['created_time'])), date('d-m-Y H:i:s'))  . ']';
                $last_login_time    = (!empty($getAdminData['last_login_time']) ? date('l, d F Y - h:i:s A', strtotime($getAdminData['last_login_time'])) . ' [' . getTimeAgo(date('d-m-Y H:i:s', strtotime($getAdminData['last_login_time'])), date('d-m-Y H:i:s')) . ']' : 'Not yet logged in!');
                $updated_time       = (!empty($getAdminData['updated_time']) ? date('l, d F Y - h:i:s A', strtotime($getAdminData['updated_time'])) . ' [' . getTimeAgo(date('d-m-Y H:i:s', strtotime($getAdminData['updated_time'])), date('d-m-Y H:i:s')) . ']' : 'Not yet logged in!');

                // response
                $response['status'] = true;
                $response['message'] = [
                    'username'          => $getAdminData['username'],
                    'email'             => $getAdminData['email'],
                    'name'              => $getAdminData['name'],
                    'avatar'            => $avatar,
                    'role'              => $getAdminData['role'],
                    'status'            => $getAdminData['status'],
                    'is_login'          => $getAdminData['is_login'],
                    'created_time'      => $created_time,
                    'last_login_time'   => $last_login_time,
                    'last_login_ip'     => (!empty($getAdminData['last_login_ip']) ? $getAdminData['last_login_ip'] : 'Not yet logged in !'),
                    'updated_time'      => $updated_time
                ];
            } else {
                $response['status'] = false;
                $response['message'] = 'Admin Account cannot be found!';
            }

            // json output
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }

    // add
    public function add()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('add_admins');

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            // create form rules validation
            $rulesValidation = [
                'add_admin_avatar' => [
                    'rules' => 'mime_in[add_admin_avatar,image/jpg,image/jpeg,image/png]|max_dims[add_admin_avatar,120,120]|max_size[add_admin_avatar,128]',
                    'errors' => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 120px height: 120px!',
                        'max_size'      => 'Maximum size image cannot be more than 128 KB!'
                    ]
                ],
                'add_admin_username' => [
                    'rules' => 'required|alpha_numeric|min_length[6]|max_length[24]',
                    'errors' => [
                        'required'      => 'Please fill in the username column first!',
                        'alpha_numeric' => 'The username field may only contain alpha numeric characters!',
                        'min_length'    => 'The username field must be at least 6 characters in length!',
                        'max_length'    => 'The username field cannot exceed 24 characters in length!'
                    ]
                ],
                'add_admin_password' => [
                    'rules' => 'required|alpha_numeric|min_length[6]|max_length[24]',
                    'errors' => [
                        'required'      => 'Please fill in the password column first!',
                        'alpha_numeric' => 'The password field may only contain alpha numeric characters!',
                        'min_length'    => 'The password field must be at least 6 characters in length!',
                        'max_length'    => 'The password field cannot exceed 24 characters in length!'
                    ]
                ],
                'add_admin_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required'      => 'Please fill in the email column first!',
                        'valid_email'   => 'Please enter a valid email!'
                    ]
                ],
                'add_admin_name' => [
                    'rules' => 'required|alpha_space|min_length[3]|max_length[48]',
                    'errors' => [
                        'required'      => 'Please fill in the name column first!',
                        'alpha_space'   => 'The name field may only contain alpha characters and space!',
                        'min_length'    => 'The name field must be at least 3 characters in length!',
                        'max_length'    => 'The name field cannot exceed 48 characters in length!'
                    ]
                ],
                'add_admin_role' => [
                    'rules' => 'required|in_list[1,2,3]',
                    'errors' => [
                        'required' => 'Please fill in the role column first!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postDataUsername   = $this->request->getPost('add_admin_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postDataPassword   = $this->request->getPost('add_admin_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postDataEmail      = $this->request->getPost('add_admin_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postDataName       = $this->request->getPost('add_admin_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postDataRole       = $this->request->getPost('add_admin_role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postDataStatus     = $this->request->getPost('add_admin_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postFile           = $this->request->getFile('add_admin_avatar');
                $checkFile          = $postFile->getFilename();

                // check account is exist
                $checkUsername = $this->admin->where('username', $postDataUsername)->first();
                if (empty($checkUsername)) {
                    // check email
                    $checkEmail = $this->admin->where('email', $postDataEmail)->first();
                    if (empty($checkEmail)) {
                        // check apakah password sama dengan username
                        if ($postDataPassword != $postDataUsername || $postDataUsername != $postDataPassword) {
                            // jika ada file yang di upload
                            if (!empty($checkFile)) {
                                $fileName = 'avatar_' . $postDataUsername . '_' . $postFile->getRandomName();
                                // uploaded file
                                $this->image->withFile($postFile)->save(FCPATH . '/assets/images/avatar/' . $fileName); // execute image imanipulation
                                $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image
                            } else {
                                $fileName = 'default.png'; // default
                            }

                            // create log data and inserting log
                            $insertLogData = [
                                'username'      => session()->get('username'),
                                'action'        => 'ADD ADMIN',
                                'description'   => 'Have successfully added a new admin account: ' . $postDataUsername,
                                'url'           => base_url('/admin/list-admin'),
                                'level'         => 4,
                                'ip_address'    => $this->request->getIPAddress(),
                                'user_agent'    => $this->request->getUserAgent()
                            ];
                            $execInsertLog = $this->log->insert($insertLogData);

                            if (isset($execInsertLog)) {
                                // inserting new admin
                                $insertAdminData = [
                                    'username'  => $postDataUsername,
                                    'password'  => password_hash($postDataPassword, PASSWORD_DEFAULT),
                                    'email'     => $postDataEmail,
                                    'name'      => $postDataName,
                                    'avatar'    => $fileName,
                                    'role'      => $postDataRole,
                                    'status'    => ($postDataStatus == 'true' || $postDataStatus == true ? 1 : 0),
                                    'is_login'  => 0
                                ];
                                $execInsertAdmin = $this->admin->insert($insertAdminData);

                                // inserting admin permission
                                $execInsertAdminPermission = $this->admin_permissions->insert(['username' => $postDataUsername]);

                                if (isset($execInsertAdmin) || isset($execInsertAdminPermission)) {
                                    $response['status'] = true;
                                    $response['message'] = "You have successfully added an admin account!";
                                } else {
                                    $response['status'] = false;
                                    $response['message'] = 'Failed to insert data, Please contact Developer!';
                                }
                            } else {
                                $response['status'] = false;
                                $response['message'] = 'Failed to insert log, Please contact Developer!';
                            }
                        } else {
                            $response['status'] = false;
                            $response['message'] = "The password cannot be the same as the username!";
                        }
                    } else {
                        $response['status'] = false;
                        $response['message'] = "The email you entered is already used by someone else, please enter another email!";
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = "Username already exists, please use another username!";
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

    // edit
    public function edit($username = null)
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('edit_admins');

        // jika tidak ada parameter pada uri
        if (is_null($username) || empty($username) || $username == null || $username == '') {
            // jika form search di jalankan
            if ($this->request->getMethod() === "post") {
                $postUsername = $this->request->getVar('search_edit_admin_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postEmail    = $this->request->getVar('search_edit_admin_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postName     = $this->request->getVar('search_edit_admin_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // check form
                if (!empty($postUsername)) {
                    $checkUsername = $this->admin->where('username', $postUsername)->first();
                    // check account
                    if (isset($checkUsername)) {
                        return redirect()->to(base_url('/admin/manage-admins/edit/' . $checkUsername['username']));
                    } else {
                        session()->setFlashData('error', 'The username you entered does not exist or has not been registered!');
                    }
                } else if (!empty($postEmail)) {
                    $checkEmail = $this->admin->where('email', $postEmail)->first();
                    // check account
                    if (isset($checkEmail)) {
                        return redirect()->to(base_url('/admin/manage-admins/edit/' . $checkEmail['username']));
                    } else {
                        session()->setFlashData('error', 'The email you entered does not exist or has not been registered!');
                    }
                } else if (!empty($postName)) {
                    $checkName = $this->admin->where('name', $postName)->first();
                    // check account
                    if (isset($checkName)) {
                        return redirect()->to(base_url('/admin/manage-admins/edit/' . $checkName['username']));
                    } else {
                        session()->setFlashData('error', 'The name you entered does not exist or has not been registered!');
                    }
                } else {
                    session()->setFlashData('error', 'Please fill out one of the forms first!');
                }
            }
        } else {
            // check admin in database by parameter uri
            $checkDataAdmin = $this->admin->where('username', $username)->first();
            // jika admin tidak ditemukan
            if (is_null($username) || empty($checkDataAdmin)) {
                return redirect()->to(base_url('/admin/manage-admins/edit'));
            }
        }

        // require data
        if (is_null($username) || empty($username) || $username == null || $username == '') {
            $data['website_title'] = 'Edit Admin';
        } else {
            $data['website_title'] = 'Edit Admin Account ' . $username;
        }

        $data['getDataAdminSession']        = $this->admin->where('username', session()->get('username'))->first();
        $data['getPermissionsAdminSession'] = $this->admin_permissions->where('username', session()->get('username'))->first();
        $data['getDataAdmin']               = $this->admin->where('username', $username)->first();

        // load layout and get page
        return view('Backend/Superadmin/ManageAdmins/Edit', $data);
    }

    // update
    public function update()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('edit_admins');

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            // create form rules validation
            $rulesValidation = [
                'data_edit_admin_username' => [
                    'rules'     => 'required|alpha_numeric|min_length[6]|max_length[24]',
                    'errors'    => [
                        'required'      => 'Please fill in the username column first!',
                        'alpha_numeric' => 'The username field may only contain alpha numeric characters!',
                        'min_length'    => 'The username field must be at least 6 characters in length!',
                        'max_length'    => 'The username field cannot exceed 24 characters in length!'
                    ]
                ],
                'data_edit_admin_email' => [
                    'rules'     => 'required|valid_email',
                    'errors'    => [
                        'required'      => 'Please fill in the email column first!',
                        'valid_email'   => 'Please enter a valid email!'
                    ]
                ],
                'data_edit_admin_name' => [
                    'rules'     => 'required|alpha_space|min_length[3]|max_length[48]',
                    'errors'    => [
                        'required'      => 'Please fill in the name column first!',
                        'alpha_space'   => 'The name field may only contain alpha characters and space!',
                        'min_length'    => 'The name field must be at least 3 characters in length!',
                        'max_length'    => 'The name field cannot exceed 48 characters in length!'
                    ]
                ],
                'data_edit_admin_avatar' => [
                    'rules'     => 'mime_in[data_edit_admin_avatar,image/jpg,image/jpeg,image/png]|max_dims[data_edit_admin_avatar,120,120]|max_size[data_edit_admin_avatar,128]',
                    'errors'    => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 120px height: 120px!',
                        'max_size'      => 'Maximum size image cannot be more than 128 KB!'
                    ]
                ],
                'data_edit_admin_role' => [
                    'rules'     => 'required|in_list[1,2,3]',
                    'errors'    => [
                        'required'      => 'Please fill in the role column first!',
                    ]
                ],
                'data_edit_admin_status' => [
                    'rules'     => 'required|in_list[0,1,2]',
                    'errors'    => [
                        'required'      => 'Please fill in the status column first!',
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                $getUsername    = $this->request->getVar('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $getAccount     = $this->admin->where('username', $getUsername)->first();
                // check account from form input username
                if (isset($getAccount)) {
                    $postUsername   = $this->request->getVar('data_edit_admin_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postPassword   = $this->request->getVar('data_edit_admin_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postEmail      = $this->request->getVar('data_edit_admin_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postName       = $this->request->getVar('data_edit_admin_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postRole       = $this->request->getVar('data_edit_admin_role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postStatus     = $this->request->getVar('data_edit_admin_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postFile       = $this->request->getFile('data_edit_admin_avatar');
                    $checkFile      = $postFile->getFilename();

                    if ($getAccount['username'] != $postUsername) {
                        $updateAdmin['username'] = $postUsername;
                    }
                    if ($getAccount['email'] != $postEmail) {
                        $updateAdmin['email'] = $postEmail;
                    }
                    if ($getAccount['name'] != $postName) {
                        $updateAdmin['name'] = $postName;
                    }
                    if ($getAccount['role'] != $postRole) {
                        $updateAdmin['role'] = $postRole;
                    }
                    if ($getAccount['status'] != $postStatus) {
                        $updateAdmin['status'] = $postStatus;
                    }

                    // jika ada file yang di upload
                    if (!empty($checkFile)) {
                        $fileName = 'avatar_' . $getAccount['username'] . '_' . $postFile->getRandomName();
                        // uploaded file
                        $this->image->withFile($postFile)->fit(120, 120, 'center')->save(FCPATH . '/assets/images/avatar/' . $fileName); // execute image imanipulation
                        $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                        $updateAdmin['avatar'] = $fileName;
                    }

                    // jika password di isi
                    if (!empty($postPassword)) {
                        // create validation
                        if (!preg_match("/^[a-zA-Z0-9]+$/", $postPassword)) {
                            $response['status'] = false;
                            $response['message'] = ['data_edit_admin_password' => 'The password field may only contain alpha numeric characters!'];
                        } else if (strlen($postPassword) < 6 || strlen($postPassword) > 24) {
                            $response['status'] = false;
                            $response['message'] = ['data_edit_admin_password' => 'The minimum length of the username column is 6 characters and the maximum is 24 characters!'];
                        } else if ($postPassword == $postUsername || $postPassword == $getUsername) {
                            $response['status'] = false;
                            $response['message'] = ['data_edit_admin_password' => 'The password field may only contain alpha numeric characters!'];
                        } else {
                            $updateAdmin['password'] = password_hash($postPassword, PASSWORD_DEFAULT);

                            if (isset($updateAdmin)) {
                                // create log data and inserting log
                                $insertLogData = [
                                    'username'      => session()->get('username'),
                                    'action'        => 'UPDATE ADMIN',
                                    'description'   => 'Have successfully updated admin account: ' . $getAccount['username'],
                                    'url'           => base_url('/admin/manage-admins/edit'),
                                    'level'         => 4,
                                    'ip_address'    => $this->request->getIPAddress(),
                                    'user_agent'    => $this->request->getUserAgent()
                                ];
                                $execInsertLog = $this->log->insert($insertLogData);

                                if (isset($execInsertLog)) {
                                    $updateAdmin['updated_time'] = date("Y-m-d H:i:s");

                                    // update admin data
                                    $execUpdateAdmin = $this->admin->set($updateAdmin)->where('username', $getAccount['username'])->update();
                                    if (isset($execUpdateAdmin)) {
                                        $response['status'] = true;
                                        $response['message'] = 'You have successfully updated admin account :' . $getAccount['username'];
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
                                $response['message'] = "No changes!";
                            }
                        }
                    } else {
                        if (isset($updateAdmin)) {
                            // create log data and inserting log
                            $insertLogData = [
                                'username'      => session()->get('username'),
                                'action'        => 'UPDATE ADMIN',
                                'description'   => 'Have successfully updated admin account: ' . $getAccount['username'],
                                'url'           => base_url('/admin/manage-admins/edit'),
                                'level'         => 4,
                                'ip_address'    => $this->request->getIPAddress(),
                                'user_agent'    => $this->request->getUserAgent()
                            ];
                            $execInsertLog = $this->log->insert($insertLogData);

                            if (isset($execInsertLog)) {
                                $updateAdmin['updated_time'] = date("Y-m-d H:i:s");

                                // update admin data
                                $execUpdateAdmin = $this->admin->set($updateAdmin)->where('username', $getAccount['username'])->update();
                                if (isset($execUpdateAdmin)) {
                                    $response['status'] = true;
                                    $response['message'] = "You have successfully updated admin account :" . $getAccount['username'];
                                } else {
                                    $response['status'] = false;
                                    $response['message'] = 'Failed to update data admin account, Please contact Developer!';
                                }
                            } else {
                                $response['status'] = false;
                                $response['message'] = 'Failed to inserting log data, Please contact Developer!';
                            }
                        } else {
                            $response['status'] = false;
                            $response['message'] = "Nothing has been changed!";
                        }
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = "Account not found in database, please try again!";
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

    // delete
    public function delete()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('delete_admins');

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];
            // get username from post
            $username = $this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // get admin account by username
            $getAdmin = $this->admin->where('username', $username)->first();
            // jika datanya ada
            if (!empty($getAdmin)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE ADMIN',
                    'description'   => 'Have successfully deleted admin account: ' . $getAdmin['username'],
                    'url'           => base_url('/admin/manage-admins/delete'),
                    'level'         => 4,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    $execDeleteAdmin = $this->admin->where('username', $getAdmin['username'])->delete();
                    $execDeletePermissions = $this->admin_permissions->where('username', $getAdmin['username'])->delete();

                    if (isset($execDeleteAdmin) || isset($execDeletePermissions)) {
                        $response['status'] = true;
                        $response['message'] = 'You have successfully deleted the admin account: ' . $username;
                    } else {
                        $response['status'] = false;
                        $response['message'] = 'Failed to delete data, Please contact Developer!';
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Failed to insert log, Please contact Developer!';
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Admin Account cannot be found!';
            }

            // json output
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }

    // edit permissions
    public function edit_permissions($username = null)
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('edit_admin_permissions');

        // jika tidak ada parameter pada uri
        if (is_null($username) || empty($username) || $username == null || $username == '') {
            // jika form search di jalankan
            if ($this->request->getMethod() === "post") {
                $postUsername = $this->request->getVar('search_edit_permissions_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // check form
                if (!empty($postUsername)) {
                    $getAccountPermissions = $this->admin_permissions->where('username', $postUsername)->first();
                    // check account
                    if (isset($getAccountPermissions)) {
                        return redirect()->to(base_url('/admin/manage-admins/edit-permissions/' . $getAccountPermissions['username']));
                    } else {
                        session()->setFlashData('error', 'The username you entered does not exist!');
                    }
                } else {
                    session()->setFlashData('error', 'Please fill out one of the forms first!');
                }
            }
        } else {
            // check admin in database by parameter uri
            $checkDataAdmin = $this->admin->where('username', $username)->first();
            // jika admin tidak ditemukan
            if (is_null($username) || empty($checkDataAdmin)) {
                return redirect()->to(base_url('/admin/manage-admins/edit-permissions'));
            }
        }

        // require data
        if (is_null($username) || empty($username) || $username == null || $username == '') {
            $data['website_title'] = 'Edit Admin Permissions';
        } else {
            $data['website_title'] = 'Edit Admin Permissions Account ' . $username;
        }
        $data['getDataAdminSession']        = $this->admin->where('username', session()->get('username'))->first();
        $data['getPermissionsAdminSession'] = $this->admin_permissions->where('username', session()->get('username'))->first();
        $data['getPermissionsAdminData']    = $this->admin_permissions->where('username', $username)->first();

        // load layout and get page
        return view('Backend/Superadmin/ManageAdmins/EditPermissions', $data);
    }

    // update permissions
    public function update_permissions()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('edit_admin_permissions');

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            $getUsername    = $this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $getPermissions = $this->admin_permissions->where('username', $getUsername)->first();
            // check account from form input username
            if (isset($getPermissions)) {
                // get post input
                $manage_admins          = $this->request->getPost('manage_admins', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $add_admins             = $this->request->getPost('add_admins', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $edit_admins            = $this->request->getPost('edit_admins', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $delete_admins          = $this->request->getPost('delete_admins', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $edit_admin_permissions = $this->request->getPost('edit_admin_permissions', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $edit_website_settings  = $this->request->getPost('edit_website_settings', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $manage_logs            = $this->request->getPost('manage_logs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $updatePermissions = [$manage_admins, $add_admins, $edit_admins, $delete_admins, $edit_admin_permissions, $edit_website_settings, $manage_logs];
                $listPermissions = '';

                for ($i = 0; $i < count($updatePermissions); $i++) {
                    if ($i == 0) {
                        $listPermissions .= '';
                    } else {
                        if ($updatePermissions[$i] == NULL) {
                            $listPermissions .= '';
                        } else {
                            $listPermissions .= ',';
                        }
                    }
                    $listPermissions .= $updatePermissions[$i];
                }

                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'UPDATE ADMIN PERMISSIONS',
                    'description'   => 'Have successfully updated permissions admin account: ' . $getPermissions['username'],
                    'url'           => base_url('/admin/manage-admins/update-permissions'),
                    'level'         => 4,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    $execUpdatePermissions = $this->admin_permissions->set('permissions', ltrim($listPermissions, ','))->where('username', $getPermissions['username'])->update();

                    if (isset($execUpdatePermissions)) {
                        $response['status'] = true;
                        $response['message'] = 'You have successfully updated permissions admin account: ' . $getPermissions['username'];
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
                $response['message'] = "Account not found in database, please try again!";
            }

            // ouput json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
