<?php

namespace App\Controllers\Backend\Admin;

use App\Controllers\Backend\BaseController;

use App\Models\HomepageModel;

class ManageHomepage extends BaseController
{
    // constructor
    function __construct()
    {
        $this->homepage = new HomepageModel();
    }

    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // sending data to view
        $data['website_title']          = 'Manage Homepage';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
        $data['getHomepage']            = $this->homepage->where('id', 1)->first();

        // load layout and get the page
        return view('Backend/Admin/ManageHomepage', $data);
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
                'hero_image' => [
                    'rules' => 'mime_in[hero_image,image/jpg,image/jpeg]|max_dims[hero_image,1600,1200]|max_size[hero_image,1000]',
                    'errors' => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg!',
                        'max_dims'      => 'Maximum dimension width: 1600px height: 1200px!',
                        'max_size'      => 'Maximum size image cannot be more than 1000 KB / 1 MB!'
                    ]
                ],
                'hero_title' => [
                    'rules' => 'required|max_length[255]|regex_match[/^[&%! a-zA-Z0-9,."]+$/]',
                    'errors' => [
                        'required'      => 'Please fill in the hero title column first!',
                        'max_length'    => 'The hero title field cannot exceed 255 characters in length!',
                        'regex_match'   => 'The hero title field is not in the correct format!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postTitle      = $this->request->getPost('hero_title');
                $postContent    = $this->request->getPost('other_content');
                $postImage      = $this->request->getFile('hero_image');
                $checkImage     = $postImage->getFilename();

                // check data in database
                $getHomepage = $this->homepage->where('id', 1)->first();
                // jika datanya ada
                if (isset($getHomepage)) {
                    // jika post imagenya di isi
                    if (!empty($checkImage)) {
                        $fileName       = 'hero_image_' . $postImage->getRandomName();
                        // uploaded file
                        $this->image->withFile($postImage)->fit(1600, 1200, 'center')->save(FCPATH . '/assets/images/bg-hero.jpg', true);
                        $postImage->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                        $updateData['hero_image'] = 'bg-hero.jpg'; // send data to query builder
                    }

                    // jika titlenya di ubah
                    if ($getHomepage['hero_title'] != $postTitle) {
                        $updateData['hero_title'] = $postTitle; // send data to query builder
                    }
                    // jika contentnnya di ubah
                    if ($getHomepage['other_content'] != $postContent) {
                        $updateData['other_content'] = $postContent; // send data to query builder
                    }

                    if (isset($updateData)) {
                        // create log data and inserting log
                        $insertLogData = [
                            'username'      => session()->get('username'),
                            'action'        => 'UPDATE HOMEPAGE',
                            'description'   => 'Have successfully updated homepage',
                            'url'           => base_url('/admin/manage-homepage'),
                            'level'         => 3,
                            'ip_address'    => $this->request->getIPAddress(),
                            'user_agent'    => $this->request->getUserAgent()
                        ];
                        $execInsertLog = $this->log->insert($insertLogData);

                        if (isset($execInsertLog)) {
                            $execUpdateData = $this->homepage->set($updateData)->where('id', 1)->update();

                            if (isset($execUpdateData)) {
                                $response['status'] = true;
                                $response['message'] = "You have successfully updated data!";
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
                        $response['message'] = 'Nothing has been changed!';
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Failed to get data, Please contact Developer!';
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
