<?php

namespace App\Controllers\Backend\Admin;

use App\Controllers\Backend\BaseController;

use App\Models\ApaItuModel;

class ApaItu extends BaseController
{
    // constructor
    function __construct()
    {
        $this->apa_itu = new ApaItuModel();
    }

    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();

        // sending data to view
        $data['website_title']          = 'Apa Itu ?';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
        $data['getApaItu']              = $this->apa_itu->where('id', 1)->first();

        // load layout and get the page
        return view('Backend/Admin/ApaItu', $data);
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
                'cover_image_apa_itu' => [
                    'rules' => 'mime_in[cover_image_apa_itu,image/jpg,image/jpeg,image/png,image/webp]|max_dims[cover_image_apa_itu,1600,1200]|max_size[cover_image_apa_itu,2000]',
                    'errors' => [
                        'mime_in'       => 'Files can only have image extensions such as: jpg or jpeg & png!',
                        'max_dims'      => 'Maximum dimension width: 1600px height: 1200px!',
                        'max_size'      => 'Maximum size image cannot be more than 2000 KB / 2 MB!'
                    ]
                ]
            ];

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // get value for method post
                $postContent    = $this->request->getPost('content_apa_itu');
                $postFile       = $this->request->getFile('cover_image_apa_itu');
                $checkFile      = $postFile->getFilename();

                // check data in database
                $getApaItu = $this->apa_itu->where('id', 1)->first();
                // jika datanya ada
                if (isset($getApaItu)) {
                    // jika post imagenya di isi
                    if (!empty($checkFile)) {
                        $fileName       = 'apa_itu_' . $postFile->getRandomName();
                        // uploaded file
                        $this->image->withFile($postFile)->save(FCPATH . '/assets/images/apa-itu/' . $fileName); // execute image imanipulation
                        $postFile->move(WRITEPATH . '/uploads/images/' . $fileName); // upload real image

                        $updateData['images'] = $fileName; // send data to query builder
                    }
                    // jika contentnnya di ubah
                    if ($getApaItu['content'] != $postContent) {
                        $updateData['content'] = $postContent; // send data to query builder
                    }

                    if (isset($updateData)) {
                        // create log data and inserting log
                        $insertLogData = [
                            'username'      => session()->get('username'),
                            'action'        => 'UPDATE APA ITU',
                            'description'   => 'Have successfully updated page apa itu?',
                            'url'           => base_url('/admin/apa-itu'),
                            'level'         => 3,
                            'ip_address'    => $this->request->getIPAddress(),
                            'user_agent'    => $this->request->getUserAgent()
                        ];
                        $execInsertLog = $this->log->insert($insertLogData);

                        if (isset($execInsertLog)) {
                            $execUpdateData = $this->apa_itu->set($updateData)->where('id', 1)->update();

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
