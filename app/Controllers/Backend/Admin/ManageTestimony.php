<?php

namespace App\Controllers\Backend\Admin;

use App\Controllers\Backend\BaseController;

use App\Models\TestimonyModel;

class ManageTestimony extends BaseController
{
    // constructor
    function __construct()
    {
        $this->testimony = new TestimonyModel();
    }

    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // sending data to view
        $data['website_title']              = 'Manage Testimony';
        $data['getDataAdminSession']        = $this->admin->where('username', session()->get('username'))->first();

        // load layout and get the page
        return view('Backend/Admin/ManageTestimony', $data);
    }

    // table list
    public function table()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // request from ajax
        if ($this->request->isAJAX()) {
            $request    = service('request');
            $postData   = $request->getPost(); // $postData = $this->request->getPost();
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
            $totalRecords = $this->testimony->select('id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->testimony->select('id');
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                // $searchQuery->groupStart();
                $searchQuery->orLike('title', $searchValue);
                // $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->testimony->select('*');
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                // $searchQuery->groupStart();
                $searchQuery->orLike('title', $searchValue);
                // $searchQuery->groupEnd();
            }
            $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);

            $data = [];
            foreach ($records as $record) {
                if (!empty($record['images'])) {
                    $getImages = (FCPATH . '/assets/images/testimony/' . $record['images']);
                    if (file_exists($getImages)) {
                        $postImage = '<img src="' . base_url('/assets/images/testimony/' . $record['images']) . '" alt="' . $record['title'] . '" width="75" height="60" />';
                    } else {
                        $postImage = '<img src="' . base_url('/assets/images/testimony/default.png') . '" alt="' . $record['title'] . '" width="75" height="60" />';
                    }
                } else {
                    $postImage = '<img src="' . base_url('/assets/images/testimony/default.png') . '" alt="' . $record['title'] . '" width="75" height="60" />';
                }

                $data[] = [
                    "images"        => $postImage,
                    "title"         => $record['title'],
                    "status"        => $record['status'],
                    "created_time"  => $record['created_time'],
                    "options"       => '<a href="javascript:void(0);" id="' . $record['id'] . '" class="btn btn-danger btn-xs shadow-none button_delete_testimony" title="Delete"><i class="bi bi-trash"></i></a>'
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

    // save
    public function save()
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
                'testi_image' => [
                    'rules' => 'uploaded[testi_image]|mime_in[testi_image,image/jpg,image/jpeg,image/png,image/webp]|max_dims[testi_image,540,540]|max_size[testi_image,512]',
                    'errors' => [
                        'uploaded'      => 'Please upload image first!',
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 540px height: 540px!',
                        'max_size'      => 'Maximum size image cannot be more than 512 KB!'
                    ]
                ],
                'testi_title' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s,&"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the title column first!',
                        'max_length'    => 'The title field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'testi_status' => [
                    'rules' => 'required|in_list[0,1]',
                    'errors' => [
                        'required' => 'Please fill in the status column first!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postTitle  = $this->request->getPost('testi_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postStatus = $this->request->getPost('testi_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postFile   = $this->request->getFile('testi_image');

                // check testimony title is exist
                $checkTestimony = $this->testimony->where('title', $postTitle)->first();
                if (empty($checkTestimony)) {
                    $fixImageName = strtolower(url_title($postTitle));
                    $fileName = 'testimony_' . preg_replace('~-~', '_', $fixImageName) . '_' . $postFile->getRandomName();
                    // uploaded file
                    $this->image->withFile($postFile)->save(FCPATH . '/assets/images/testimony/' . $fileName); // execute image imanipulation
                    $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'ADD TESTIMONY',
                        'description'   => 'Have successfully added a new testimony title: ' . $postTitle,
                        'url'           => base_url('/admin/manage-testimony'),
                        'level'         => 3,
                        'ip_address'    => $this->request->getIPAddress(),
                        'user_agent'    => $this->request->getUserAgent()
                    ];
                    $execInsertLog = $this->log->insert($insertLogData);

                    if (isset($execInsertLog)) {
                        // inserting new admin
                        $insertData = [
                            'images'        => $fileName,
                            'title'         => $postTitle,
                            'status'        => $postStatus
                        ];
                        $execInsertData = $this->testimony->insert($insertData);

                        if (isset($execInsertData)) {
                            $response['status'] = true;
                            $response['message'] = "You have successfully added new testimony!";
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
                    $response['message'] = "This testimony already exists, please use another title!";
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

        // request from ajax
        if ($this->request->isAJAX()) {
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];
            // get id from post
            $getID = $this->request->getPost('id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // get testimony from post ID
            $getTestimony = $this->testimony->where('id', $getID)->first();
            // jika datanya ada
            if (!empty($getTestimony)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE TESTIMONY',
                    'description'   => 'Have successfully deleted testimony title: ' . $getTestimony['title'],
                    'url'           => base_url('/admin/manage-testimony'),
                    'level'         => 3,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    // execution query delete
                    $execDeleteTestimony = $this->testimony->where('title', $getTestimony['title'])->delete();

                    if (isset($execDeleteTestimony)) {
                        $response['status']  = true;
                        $response['message'] = 'Testimony has been successfully deleted!';
                    } else {
                        $response['status']  = false;
                        $response['message'] = 'Failed to delete data, please contact Developer!';
                    }
                } else {
                    $response['status']  = false;
                    $response['message'] = 'Failed to insert log, please contact Developer!';
                }
            } else {
                $response['status']  = false;
                $response['message'] = 'Data not found, please try again later!';
            }

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
