<?php

namespace App\Controllers\Backend\Admin;

use App\Controllers\Backend\BaseController;

use App\Models\PricelistModel;
use App\Models\QuestionsModel;

class Bagaimana extends BaseController
{
    // constructor
    function __construct()
    {
        $this->price_list = new PricelistModel();
        $this->questions  = new QuestionsModel();
    }

    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // sending data to view
        $data['website_title']          = 'Bagaimana';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
        $data['getListQuestion']        = $this->questions->where('status', 1)->orderBy('created_time', 'desc')->findAll();

        // load layout and get the page
        return view('Backend/Admin/Bagaimana', $data);
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
            $totalRecords = $this->price_list->select('id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->price_list->select('id');
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                // $searchQuery->groupStart();
                $searchQuery->orLike('title', $searchValue)->orLike('url', $searchValue);
                // $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->price_list->select('*');
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                // $searchQuery->groupStart();
                $searchQuery->orLike('title', $searchValue)->orLike('url', $searchValue);
                // $searchQuery->groupEnd();
            }
            $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);

            $data = [];
            foreach ($records as $record) {
                if (!empty($record['images'])) {
                    $getImages = (FCPATH . '/assets/images/price-list/' . $record['images']);
                    if (file_exists($getImages)) {
                        $postImage = '<img src="' . base_url('/assets/images/price-list/' . $record['images']) . '" alt="' . $record['title'] . '" height="75" width="75" />';
                    } else {
                        $postImage = '<img src="' . base_url('/assets/images/price-list/default.jpg') . '" alt="' . $record['title'] . '" height="75" width="75" />';
                    }
                } else {
                    $postImage = '<img src="' . base_url('/assets/images/price-list/default.jpg') . '" alt="' . $record['title'] . '" height="75" width="75" />';
                }

                $data[] = [
                    "images"        => $postImage,
                    "title"         => $record['title'],
                    "url"           => $record['url'],
                    "status"        => $record['status'],
                    "created_time"  => $record['created_time'],
                    "options"       => '<a href="javascript:void(0);" id="' . $record['id'] . '" class="btn btn-danger btn-xs shadow-none button_delete_pricelist" title="Delete"><i class="bi bi-trash"></i></a>'
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
                'pricelist_image' => [
                    'rules' => 'uploaded[pricelist_image]|mime_in[pricelist_image,image/jpg,image/jpeg,image/png]|max_dims[pricelist_image,995,995]|max_size[pricelist_image,512]',
                    'errors' => [
                        'uploaded'      => 'Please upload image first!',
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 995px height: 995px!',
                        'max_size'      => 'Maximum size image cannot be more than 512 KB!'
                    ]
                ],
                'pricelist_title' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the title column first!',
                        'max_length'    => 'The title field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'pricelist_url' => [
                    'rules' => 'required|valid_url|max_length[255]',
                    'errors' => [
                        'required'      => 'Please fill in the url column first!',
                        'valid_url'     => 'The url field must contain a valid URL!',
                        'max_length'    => 'The url field cannot exceed 255 characters in length!'
                    ]
                ],
                'pricelist_status' => [
                    'rules' => 'required|in_list[0,1]',
                    'errors' => [
                        'required' => 'Please fill in the status column first!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postTitle  = $this->request->getPost('pricelist_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postURL    = $this->request->getPost('pricelist_url', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postStatus = $this->request->getPost('pricelist_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postFile   = $this->request->getFile('pricelist_image');

                // check data from title is exist
                $checkPricelistExist = $this->price_list->where('title', $postTitle)->first();
                if (empty($checkPricelistExist)) {
                    $fileName = 'price_list_' . $postFile->getRandomName();
                    // uploaded file
                    $this->image->withFile($postFile)->fit(995, 992, 'center')->save(FCPATH . '/assets/images/price-list/' . $fileName); // execute image imanipulation
                    $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'ADD PRICELIST',
                        'description'   => 'Have successfully added a new price list title: ' . $postTitle,
                        'url'           => base_url('/admin/bagaimana'),
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
                            'url'           => $postURL,
                            'status'        => $postStatus
                        ];
                        $execInsertData = $this->price_list->insert($insertData);

                        if (isset($execInsertData)) {
                            $response['status'] = true;
                            $response['message'] = "You have successfully added new price list!";
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
                    $response['message'] = "This price list already exists, please use another title!";
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
            $getPricelist = $this->price_list->where('id', $getID)->first();
            // jika datanya ada
            if (!empty($getPricelist)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE PRICELIST',
                    'description'   => 'Have successfully deleted price list title: ' . $getPricelist['title'],
                    'url'           => base_url('/admin/bagaimana'),
                    'level'         => 3,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    // delete images
                    $deleteImage = unlink(FCPATH . '/assets/images/price-list/' . $getPricelist['images']);
                    if (isset($deleteImage)) {
                        // execution query delete
                        $execDeleteData = $this->price_list->where('id', $getID)->delete();
                        if (isset($execDeleteData)) {
                            $response['status']  = true;
                            $response['message'] = 'Price list has been successfully deleted!';
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

    // add question and answer
    public function add_question()
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
                'post_question' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[,?a-zA-Z0-9\s"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the question column first!',
                        'max_length'    => 'The question field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The question field is not in the correct format!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postQuestion = $this->request->getPost('post_question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postAnswer   = $this->request->getPost('post_answer');

                // check data from title is exist
                $checkQuestionIsExist = $this->questions->where('question', $postQuestion)->first();
                if (empty($checkQuestionIsExist)) {
                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'ADD QUESTION AND ANSWER',
                        'description'   => 'Have successfully added a new question and answer question: ' . $postQuestion,
                        'url'           => base_url('/admin/bagaimana'),
                        'level'         => 3,
                        'ip_address'    => $this->request->getIPAddress(),
                        'user_agent'    => $this->request->getUserAgent()
                    ];
                    $execInsertLog = $this->log->insert($insertLogData);

                    if (isset($execInsertLog)) {
                        // inserting new admin
                        $insertData = [
                            'question'  => $postQuestion,
                            'answer'    => $postAnswer
                        ];
                        $execInsertData = $this->questions->insert($insertData);

                        if (isset($execInsertData)) {
                            $response['status'] = true;
                            $response['message'] = "You have successfully added new question and answer!";
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
                    $response['message'] = "This question already exists, please use another question!";
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

    // delete question and answer
    public function delete_question()
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
            $getData = $this->questions->where('id', $getID)->first();
            // jika datanya ada
            if (!empty($getData)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE QUESTION AND ANSWER',
                    'description'   => 'Have successfully deleted question: ' . $getData['question'],
                    'url'           => base_url('/admin/bagaimana'),
                    'level'         => 3,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    // execution query delete
                    $execDeleteData = $this->questions->where('id', $getID)->delete();
                    if (isset($execDeleteData)) {
                        $response['status']  = true;
                        $response['message'] = 'Question has been successfully deleted!';
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
