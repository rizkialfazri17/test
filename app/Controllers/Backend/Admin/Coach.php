<?php

namespace App\Controllers\Backend\Admin;

use App\Controllers\Backend\BaseController;

use App\Models\CoachModel;

class Coach extends BaseController
{
    // constructor
    function __construct()
    {
        $this->coach = new CoachModel();
    }

    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // sending data to view
        $data['website_title']          = 'Coach';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();

        // load layout and get the page
        return view('Backend/Admin/Coach', $data);
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
            $totalRecords = $this->coach->select('id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->coach->select('id');
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                // $searchQuery->groupStart();
                $searchQuery->orLike('title', $searchValue)->orLike('name', $searchValue);
                // $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->coach->select('*');
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                // $searchQuery->groupStart();
                $searchQuery->orLike('title', $searchValue)->orLike('name', $searchValue);
                // $searchQuery->groupEnd();
            }
            $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);

            $data = [];
            foreach ($records as $record) {
                if (!empty($record['images'])) {
                    $getImages = (FCPATH . '/assets/images/coach/' . $record['images']);
                    if (file_exists($getImages)) {
                        $postImage = '<img src="' . base_url('/assets/images/coach/' . $record['images']) . '" alt="' . $record['name'] . '" height="90" width="75" />';
                    } else {
                        $postImage = '<img src="' . base_url('/assets/images/coach/default.png') . '" alt="' . $record['name'] . '" height="90" width="75" />';
                    }
                } else {
                    $postImage = '<img src="' . base_url('/assets/images/coach/default.png') . '" alt="' . $record['name'] . '" height="90" width="75" />';
                }

                $data[] = [
                    "images"        => $postImage,
                    "title"         => $record['title'],
                    "name"          => $record['name'],
                    "since"         => $record['since'],
                    "created_time"  => $record['created_time'],
                    "options"       => '<a href="javascript:void(0);" id="' . $record['id'] . '" class="btn btn-danger btn-xs shadow-none button_delete_coach" title="Delete"><i class="bi bi-trash"></i></a>'
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

    // add
    public function add()
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
                'coach_images' => [
                    'rules' => 'uploaded[coach_images]|mime_in[coach_images,image/jpg,image/jpeg,image/png]|max_dims[coach_images,640,800]|max_size[coach_images,512]',
                    'errors' => [
                        'uploaded'      => 'Please upload image first!',
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 640px height: 800px!',
                        'max_size'      => 'Maximum size image cannot be more than 512 KB!'
                    ]
                ],
                'coach_title' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the title column first!',
                        'max_length'    => 'The title field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'coach_name' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the name column first!',
                        'max_length'    => 'The name field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The name field is not in the correct format!'
                    ]
                ],
                'coach_since' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the since column first!',
                        'max_length'    => 'The since field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The since field is not in the correct format!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postTitle  = $this->request->getPost('coach_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postName   = $this->request->getPost('coach_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postSince  = $this->request->getPost('coach_since', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postFile   = $this->request->getFile('coach_images');

                // check data from title is exist
                $checkCoach = $this->coach->where('name', $postName)->first();
                if (empty($checkCoach)) {
                    $fileName = 'coach_' . $postFile->getRandomName();
                    // uploaded file
                    $this->image->withFile($postFile)->save(FCPATH . '/assets/images/coach/' . $fileName); // execute image imanipulation
                    $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'ADD COACH',
                        'description'   => 'Have successfully added a new coach name: ' . $postName,
                        'url'           => base_url('/admin/coach'),
                        'level'         => 3,
                        'ip_address'    => $this->request->getIPAddress(),
                        'user_agent'    => $this->request->getUserAgent()
                    ];
                    $execInsertLog = $this->log->insert($insertLogData);

                    if (isset($execInsertLog)) {
                        // inserting new admin
                        $insertData = [
                            'images'    => $fileName,
                            'title'     => $postTitle,
                            'name'      => $postName,
                            'since'     => $postSince
                        ];
                        $execInsertData = $this->coach->insert($insertData);

                        if (isset($execInsertData)) {
                            $response['status'] = true;
                            $response['message'] = "You have successfully added new coach!";
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
                    $response['message'] = "This coach already exists, please use another name!";
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
            // get data from post ID
            $getCoach = $this->coach->where('id', $getID)->first();
            // jika datanya ada
            if (!empty($getCoach)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE COACH',
                    'description'   => 'Have successfully deleted coach name: ' . $getCoach['name'],
                    'url'           => base_url('/admin/bagaimana'),
                    'level'         => 3,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    // delete images
                    $deleteImage = unlink(FCPATH . '/assets/images/coach/' . $getCoach['images']);
                    if (isset($deleteImage)) {
                        // execution query delete
                        $execDeleteData = $this->coach->where('id', $getID)->delete();
                        if (isset($execDeleteData)) {
                            $response['status']  = true;
                            $response['message'] = 'Coach has been successfully deleted!';
                        } else {
                            $response['status']  = false;
                            $response['message'] = 'Failed to delete data, please contact Developer!';
                        }
                    } else {
                        $response['status']  = false;
                        $response['message'] = 'Failed to delete image, please contact Developer!';
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
