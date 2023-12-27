<?php

namespace App\Controllers\Backend\Staff;

use App\Controllers\Backend\BaseController;

class Post extends BaseController
{
    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // sending data to view
        $data['website_title']              = lang('Pages.post.title');
        $data['getDataAdminSession']        = $this->admin->where('username', session()->get('username'))->first();
        $data['countTotalCategory']         = $this->post_category->countAll();
        $data['countTotalPostPublished']    = $this->post->where('status', 1)->countAllResults();
        $data['countTotalPostHidden']       = $this->post->where('status', 0)->countAllResults();
        $data['countTotalPostViewed']       = $this->post->getTotalCountViewers();
        $data['getListPostCategory']        = $this->post->select('category')->distinct()->findAll();
        $data['getListPostAuthor']          = $this->post->select('author')->distinct()->findAll();
        $data['getListPostStatus']          = $this->post->select('status')->distinct()->findAll();

        // load layout and get the page
        return view('Backend/Staff/Post/Index', $data);
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

            // Custom filter
            $searchPostByCategory   = $dtpostData['searchPostByCategory'];
            $searchPostByAuthor     = $dtpostData['searchPostByAuthor'];
            $searchPostByStatus     = $dtpostData['searchPostByStatus'];

            // Total number of records without filtering
            $totalRecords = $this->post->select('id')->countAllResults();

            // Total number of records with filtering
            $searchQuery = $this->post->select('id');
            // group start
            if ($searchValue != '' || $searchPostByCategory != '' || $searchPostByAuthor != '' || $searchPostByStatus != '') {
                $searchQuery->groupStart();
            }
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                $searchQuery->orLike('title', $searchValue);
            }
            // jika ada yang di filter melalui costume filter
            if ($searchPostByCategory != '') {
                $searchQuery->groupStart();
                $searchQuery->where('category', $searchPostByCategory);
                $searchQuery->groupEnd();
            }
            if ($searchPostByAuthor != '') {
                $searchQuery->groupStart();
                $searchQuery->where('author', $searchPostByAuthor);
                $searchQuery->groupEnd();
            }
            if ($searchPostByStatus != '') {
                $searchQuery->groupStart();
                $searchQuery->where('status', $searchPostByStatus);
                $searchQuery->groupEnd();
            }
            // group end
            if ($searchValue != '' || $searchPostByCategory != '' || $searchPostByAuthor != '' || $searchPostByStatus != '') {
                $searchQuery->groupEnd();
            }
            $totalRecordwithFilter = $searchQuery->countAllResults();

            // Fetch records
            $searchQuery = $this->post->select('*');
            // group start
            if ($searchValue != '' || $searchPostByCategory != '' || $searchPostByAuthor != '' || $searchPostByStatus != '') {
                $searchQuery->groupStart();
            }
            // jika ada yang mencari di kolom search
            if ($searchValue != '') {
                $searchQuery->orLike('title', $searchValue);
            }
            // jika ada yang di filter melalui costume filter
            if ($searchPostByCategory != '') {
                $searchQuery->groupStart();
                $searchQuery->where('category', $searchPostByCategory);
                $searchQuery->groupEnd();
            }
            if ($searchPostByAuthor != '') {
                $searchQuery->groupStart();
                $searchQuery->where('author', $searchPostByAuthor);
                $searchQuery->groupEnd();
            }
            if ($searchPostByStatus != '') {
                $searchQuery->groupStart();
                $searchQuery->where('status', $searchPostByStatus);
                $searchQuery->groupEnd();
            }
            // group end
            if ($searchValue != '' || $searchPostByCategory != '' || $searchPostByAuthor != '' || $searchPostByStatus != '') {
                $searchQuery->groupEnd();
            }
            $records = $searchQuery->orderBy($columnName, $columnSortOrder)->findAll($rowperpage, $start);

            $data = [];
            foreach ($records as $record) {
                if (!empty($record['images'])) {
                    $getPostImages = 'assets/images/post/' . $record['images'];
                    if (file_exists($getPostImages)) {
                        $postImage = '<img src="' . base_url('/assets/images/post/' . $record['images']) . '" alt="' . $record['title'] . '" width="75" height="40" />';
                    } else {
                        $postImage = '<img src="' . base_url('/assets/images/post/default.png') . '" alt="' . $record['title'] . '" width="75" height="40" />';
                    }
                } else {
                    $postImage = '<img src="' . base_url('/assets/images/post/default.png') . '" alt="' . $record['title'] . '" width="75" height="40" />';
                }

                $data[] = [
                    "category"      => $record['category'],
                    "title"         => $record['title'],
                    "images"        => $postImage,
                    "author"        => $record['author'],
                    "viewers"       => $record['viewers'],
                    "status"        => $record['status'],
                    "created_time"  => $record['created_time'],
                    "options"       => '<a href="' . base_url('/news/' . $record['slug']) . '" target="blank" class="btn btn-primary btn-xs shadow-none" title="View Detail"><i class="bi bi-eye"></i></a> 
                                        <a href="' . base_url('/admin/post/edit/' . $record['slug']) . '" class="btn btn-success btn-xs shadow-none  title="Edit Post"><i class="bi bi-pencil-square"></i></a> 
                                        <a href="javascript:void(0);" id="' . $record['slug'] . '" class="btn btn-danger btn-xs shadow-none button_delete_post" title="Delete"><i class="bi bi-trash"></i></a>'
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

        // sending data to view
        $data['website_title']          = 'Add New Post';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
        $data['getListCategory']        = $this->post_category->select('name')->distinct()->findAll();

        // load layout and get the page
        return view('Backend/Staff/Post/Add', $data);
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
                'post_category' => [
                    'rules' => 'required|alpha',
                    'errors' => [
                        'required'      => 'Please select a category first!',
                        'alpha'         => 'The category field may only contain alphabet characters!',
                    ]
                ],
                'post_images' => [
                    'rules' => 'mime_in[post_images,image/jpg,image/jpeg,image/png,image/webp]|max_dims[post_images,1920,1280]|max_size[post_images,2000]',
                    'errors' => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 1920px height: 1280px!',
                        'max_size'      => 'Maximum size image cannot be more than 2000 KB / 2 MB!'
                    ]
                ],
                'post_title' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s,&"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the title column first!',
                        'max_length'    => 'The title field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'post_description' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s,.&"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the description column first!',
                        'max_length'    => 'The description field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'post_status' => [
                    'rules' => 'required|in_list[0,1]',
                    'errors' => [
                        'required' => 'Please fill in the status column first!'
                    ]
                ],
                'post_is_view' => [
                    'rules' => 'required|in_list[0,1]',
                    'errors' => [
                        'required' => 'Please fill in the show viewers column first!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postCategory       = $this->request->getPost('post_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postTitle          = $this->request->getPost('post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postDescription    = $this->request->getPost('post_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postStatus         = $this->request->getPost('post_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postIsView         = $this->request->getPost('post_is_view', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postAuthor         = $this->request->getPost('post_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $postContent        = $this->request->getPost('post_content');

                $postSlug           = strtolower(url_title($postTitle));
                $postFile           = $this->request->getFile('post_images');
                $checkFile          = $postFile->getFilename();

                // check post is exist
                $checkSlug = $this->post->where('slug', $postSlug)->first();
                if (empty($checkSlug)) {
                    // jika ada file yang di upload
                    if (!empty($checkFile)) {
                        $fileName = 'post_' . preg_replace('~-~', '_', $postSlug) . '_' . $postFile->getRandomName();

                        // uploaded file
                        $this->image->withFile($postFile)->save(FCPATH . '/assets/images/post/' . $fileName); // execute image imanipulation
                        $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image
                    } else {
                        $fileName = null;
                    }

                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'ADD POST',
                        'description'   => 'Have successfully added a new post title: ' . $postTitle,
                        'url'           => base_url('/admin/post/add'),
                        'level'         => 2,
                        'ip_address'    => $this->request->getIPAddress(),
                        'user_agent'    => $this->request->getUserAgent()
                    ];
                    $execInsertLog = $this->log->insert($insertLogData);

                    if (isset($execInsertLog)) {
                        // inserting new admin
                        $insertPostData = [
                            'category'      => $postCategory,
                            'slug'          => $postSlug,
                            'title'         => $postTitle,
                            'description'   => $postDescription,
                            'images'        => $fileName,
                            'content'       => $postContent,
                            'author'        => (!empty($postAuthor) ? $postAuthor : session()->get('username')),
                            'is_view'       => $postIsView,
                            'status'        => $postStatus
                        ];
                        $execInsertPostData = $this->post->insert($insertPostData);

                        if (isset($execInsertPostData)) {
                            $response['status'] = true;
                            $response['message'] = "You have successfully added new post!";
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
                    $response['message'] = "This post already exists, please use another title!";
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
    public function edit($slug = null)
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // jika slug uri parameternya tidak kosong
        if (!empty($slug) || $slug != '') {
            // check slug in database tbl_post
            $checkSlug = $this->post->where('slug', $slug)->first();
            // jika slugnya ada atau valid
            if (isset($checkSlug) || !empty($checkSlug)) {
                // sending data to view
                $data['website_title']          = 'Edit Post : ' . $checkSlug['title'];
                $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
                $data['getListCategory']        = $this->post_category->select('name')->distinct()->findAll();
                $data['getPost']                = $this->post->where('slug', $slug)->first();

                // load layout and get the page
                return view('Backend/Staff/Post/Edit', $data);
            } else {
                return redirect()->to(base_url('/admin/post'));
            }
        } else {
            return redirect()->to(base_url('/admin/post'));
        }
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
                'post_category' => [
                    'rules' => 'required|alpha',
                    'errors' => [
                        'required'      => 'Please select a category first!',
                        'alpha'         => 'The category field may only contain alphabet characters!',
                    ]
                ],
                'post_images' => [
                    'rules' => 'mime_in[post_images,image/jpg,image/jpeg,image/png,image/webp]|max_dims[post_images,1920,1280]|max_size[post_images,2000]',
                    'errors' => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 1920px height: 1280px!',
                        'max_size'      => 'Maximum size image cannot be more than 2000 KB / 2 MB!'
                    ]
                ],
                'post_title' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s,&"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the title column first!',
                        'max_length'    => 'The title field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'post_description' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[a-zA-Z0-9\s,.&"]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the description column first!',
                        'max_length'    => 'The description field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The title field is not in the correct format!'
                    ]
                ],
                'post_author' => [
                    'rules' => 'required|alpha_numeric_space|max_length[24]',
                    'errors' => [
                        'required'      => 'Please fill in the author column first!',
                        'max_length'    => 'The author field cannot exceed 24 characters in length!',
                    ]
                ],
                'post_status' => [
                    'rules' => 'required|in_list[0,1]',
                    'errors' => [
                        'required'      => 'Please fill in the status column first!'
                    ]
                ],
                'post_is_view' => [
                    'rules' => 'required|in_list[0,1]',
                    'errors' => [
                        'required'      => 'Please fill in the show viewers column first!'
                    ]
                ],
                'post_viewers' => [
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required'      => 'Please fill in the viewers column first!',
                        'numeric'       => 'Viewers can only be filled in with numbers!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get slug from 
                $getSlug = $this->request->getPost('post_slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                // get post data from slug
                $getPost = $this->post->where('slug', $getSlug)->first();
                // jika slugnya benar ada
                if (!empty($getPost)) {
                    // get value for method post
                    $postCategory       = $this->request->getPost('post_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postTitle          = $this->request->getPost('post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postDescription    = $this->request->getPost('post_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postStatus         = $this->request->getPost('post_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postIsView         = $this->request->getPost('post_is_view', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postIsViewers      = $this->request->getPost('post_viewers', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postAuthor         = $this->request->getPost('post_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postContent        = $this->request->getPost('post_content');
                    $postFile           = $this->request->getFile('post_images');
                    $checkFile          = $postFile->getFilename();

                    // check jika ada perubahan pada value dan database
                    if ($getPost['category'] != $postCategory) {
                        $updatePostData['category'] = $postCategory;
                    }
                    if ($getPost['title'] != $postTitle) {
                        $updatePostData['title'] = $postTitle;
                    }
                    if ($getPost['description'] != $postDescription) {
                        $updatePostData['description'] = $postDescription;
                    }
                    if ($getPost['status'] != $postStatus) {
                        $updatePostData['status'] = $postStatus;
                    }
                    if ($getPost['is_view'] != $postIsView) {
                        $updatePostData['is_view'] = $postIsView;
                    }
                    if ($getPost['viewers'] != $postIsViewers) {
                        $updatePostData['viewers'] = $postIsViewers;
                    }
                    if ($getPost['author'] != $postAuthor) {
                        $updatePostData['author'] = $postAuthor;
                    }
                    if (!empty($postContent)) {
                        $updatePostData['content'] = $postContent;
                    }
                    // jika ada file yang di upload
                    if (!empty($checkFile)) {
                        $fileName = 'post_' . preg_replace('~-~', '_', $getPost['slug']) . '_' . $postFile->getRandomName();

                        // uploaded file
                        $this->image->withFile($postFile)->save(FCPATH . '/assets/images/post/' . $fileName); // execute image imanipulation
                        $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                        $updatePostData['images'] = $fileName;
                    }
                    // check jika ada data yang di ubah maka
                    if (isset($updatePostData)) {
                        // check post is exist
                        // create log data and inserting log
                        $insertLogData = [
                            'username'      => session()->get('username'),
                            'action'        => 'UPDATE POST',
                            'description'   => 'Have successfully updated post title: ' . $postTitle,
                            'url'           => base_url('/admin/post/edit'),
                            'level'         => 2,
                            'ip_address'    => $this->request->getIPAddress(),
                            'user_agent'    => $this->request->getUserAgent()
                        ];
                        $execInsertLog = $this->log->insert($insertLogData);

                        if (isset($execInsertLog)) {
                            $updatePostData['updated_time'] = date("Y-m-d H:i:s");
                            $execUpdatePostData = $this->post->set($updatePostData)->where('slug', $getPost['slug'])->update();

                            if (isset($execUpdatePostData)) {
                                $response['status'] = true;
                                $response['message'] = "You have successfully updated the post!";
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
                        $response['message'] = "Nothing has been changed!";
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = "Post slug could not be found, please try again later!";
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
            // get slug from post
            $slug = $this->request->getPost('slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // get post from slug
            $getPost = $this->post->where('slug', $slug)->first();
            // jika datanya ada
            if (!empty($getPost)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE POST',
                    'description'   => 'Have successfully deleted post title: ' . $getPost['title'],
                    'url'           => base_url('/admin/post'),
                    'level'         => 2,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    // execution query delete
                    $execDeletePost = $this->post->where('title', $getPost['title'])->delete();

                    if (isset($execDeletePost)) {
                        $response['status']  = true;
                        $response['message'] = 'Post has been successfully deleted!';
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

    // table category
    public function table_category()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // request from ajax
        if ($this->request->isAJAX()) {
            $data = [];
            $data['token']  = csrf_hash();
            $data['data']   = $this->post_category->select('*')->orderBy('id', 'desc')->findAll();

            // output json
            return $this->response->setJSON($data);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }

    // add category
    public function add_category()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // request from ajax
        if ($this->request->isAJAX()) {
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];
            $postCategory = $this->request->getPost('categoryName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // create validation
            if (empty($postCategory) || $postCategory == '') {
                $response['status']  = false;
                $response['message'] = 'Please enter category name first!';
            } else if (!preg_match("/^[A-Z]+$/", $postCategory)) {
                $response['status']  = false;
                $response['message'] = 'Category names can only be in alphabet A-Z and uppercase letters!';
            } else if (strlen($postCategory) < 1 || strlen($postCategory) > 24) {
                $response['status']  = false;
                $response['message'] = 'The maximum length of the category name is 24 characters!';
            } else {
                // check category is exist
                $checkCategory = $this->post_category->where('name', $postCategory)->first();
                // jika categorynya sudah ada
                if (empty($checkCategory)) {
                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'ADD POST CATEGORY',
                        'description'   => 'Have successfully added a new category: ' . $postCategory,
                        'url'           => base_url('/admin/post'),
                        'level'         => 2,
                        'ip_address'    => $this->request->getIPAddress(),
                        'user_agent'    => $this->request->getUserAgent()
                    ];
                    $execInsertLog = $this->log->insert($insertLogData);

                    if (isset($execInsertLog)) {
                        $insertCategoryData = ['name' => $postCategory];
                        $execInsertCategory = $this->post_category->insert($insertCategoryData);

                        if (isset($execInsertCategory)) {
                            $response['status']  = true;
                            $response['message'] = 'The ' . $postCategory . ' category has been successfully added!';
                        } else {
                            $response['status']  = false;
                            $response['message'] = 'Failed to insert data, please contact developer!';
                        }
                    } else {
                        $response['status']  = false;
                        $response['message'] = 'Failed to insert log, please contact Developer!';
                    }
                } else {
                    $response['status']  = false;
                    $response['message'] = 'Category name already exists or has been registered!';
                }
            }

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }

    // delete category
    public function delete_category()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // request from ajax
        if ($this->request->isAJAX()) {
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];
            $postID = $this->request->getPost('id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // check category is exist
            $postID = $this->post_category->where('id', $postID)->first();
            // jika categorynya sudah ada
            if (!empty($postID)) {
                // create log data and inserting log
                $insertLogData = [
                    'username'      => session()->get('username'),
                    'action'        => 'DELETE POST CATEGORY',
                    'description'   => 'Have successfully deleted category: ' . $postID['name'],
                    'url'           => base_url('/admin/post'),
                    'level'         => 2,
                    'ip_address'    => $this->request->getIPAddress(),
                    'user_agent'    => $this->request->getUserAgent()
                ];
                $execInsertLog = $this->log->insert($insertLogData);

                if (isset($execInsertLog)) {
                    // execution query delete
                    $execDeleteCategory = $this->post_category->where('name', $postID['name'])->delete();

                    if (isset($execDeleteCategory)) {
                        $response['status']  = true;
                        $response['message'] = 'Category has been successfully deleted!';
                    } else {
                        $response['status']  = false;
                        $response['message'] = 'Failed to delete data, please contact developer!';
                    }
                } else {
                    $response['status']  = false;
                    $response['message'] = 'Failed to insert log, please contact developer!';
                }
            } else {
                $response['status']  = false;
                $response['message'] = 'Category ID not found, please try again later!';
            }

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
