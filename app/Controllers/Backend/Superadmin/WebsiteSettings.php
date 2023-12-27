<?php

namespace App\Controllers\Backend\Superadmin;

use App\Controllers\Backend\BaseController;

class WebsiteSettings extends BaseController
{
    // index
    public function index()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('website_settings');

        // require data
        $data['website_title']          = 'Website Settings';
        $data['getDataAdminSession']    = $this->admin->where('username', session()->get('username'))->first();
        $data['getListCategory']        = $this->website_config->groupBy('category')->orderBy('id')->get()->getResultArray();
        $data['getListAll']             = $this->website_config->where('status', 1)->get()->getResultArray();

        // load layout and get page
        return view('Backend/Superadmin/WebsiteSettings', $data);
    }

    // update
    public function update()
    {
        checkAdminLogin();
        checkAdminAccount();
        checkAdminSuspended();
        checkSuperadminAccess('website_settings');

        // request from ajax
        if ($this->request->isAJAX()) {
            // default response
            $response = ['status' => false, 'message' => [], 'token' => csrf_hash()];

            // get form
            $getForm = $this->request->getPost('form_website_config', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // for general
            if ($getForm == 'general') {
                $rulesValidation = [
                    'website_name' => [
                        'rules'     => 'required|regex_match[/^[a-zA-Z0-9 -#,&"]+$/]|min_length[2]|max_length[24]',
                        'errors'    => [
                            'required'      => 'Please fill in the website name column first!',
                            'regex_match'   => 'The website name field may only contain alpha numeric & space characters!',
                            'min_length'    => 'The website name field must be at least 2 characters in length!',
                            'max_length'    => 'The website name field cannot exceed 24 characters in length!'
                        ]
                    ],
                    'website_title' => [
                        'rules'     => 'required|regex_match[/^[a-zA-Z0-9 -#,&"]+$/]|min_length[4]|max_length[60]',
                        'errors'    => [
                            'required'      => 'Please fill in the website title column first!',
                            'regex_match'   => 'The website title field may only contain alpha numeric & space characters!',
                            'min_length'    => 'The website title field must be at least 4 characters in length!',
                            'max_length'    => 'The website title field cannot exceed 60 characters in length!'
                        ]
                    ],
                    'website_favicon' => [
                        'rules'     => 'ext_in[website_favicon,png]|max_dims[website_favicon,512,512]|max_size[website_favicon,256]',
                        'errors'    => [
                            'ext_in'        => 'Files can only have the .png image extension!',
                            'max_dims'      => 'Maximum dimension width: 512px height: 512px!',
                            'max_size'      => 'Maximum size image cannot be more than 256 KB!'
                        ]
                    ],
                    'website_theme_color' => [
                        'rules'     => 'required|min_length[7]|max_length[7]',
                        'errors'    => [
                            'required'      => 'Please fill in the website theme color column first!',
                            'min_length'    => 'The website theme color field must be at least 7 characters in length!',
                            'max_length'    => 'The website theme color field cannot exceed 7 characters in length!'
                        ]
                    ]
                ];
            }

            // for seo
            if ($getForm == 'seo') {
                $rulesValidation = [
                    'website_description' => [
                        'rules'     => 'required|max_length[180]|regex_match[/^[a-zA-Z0-9\s,.&-]+$/]',
                        'errors'    => [
                            'required'      => 'Please fill in the website description column first!',
                            'max_length'    => 'The website description field cannot exceed 180 characters in length!'
                        ]
                    ],
                    'website_keywords' => [
                        'rules'     => 'required|max_length[150]|regex_match[/^[a-zA-Z0-9\s, ]+$/]',
                        'errors'    => [
                            'required'      => 'Please fill in the website title column first!',
                            'max_length'    => 'The website title field cannot exceed 150 characters in length!'
                        ]
                    ],
                    'website_image' => [
                        'rules'     => 'ext_in[website_image,png,jpg]|max_dims[website_image,1200,630]|max_size[website_image,512]',
                        'errors'    => [
                            'ext_in'        => 'Files can only have the .png or .jpg image extension!',
                            'max_dims'      => 'Maximum dimension width: 1200px height: 630px!',
                            'max_size'      => 'Maximum size image cannot be more than 512 KB!'
                        ]
                    ],
                ];
            }

            // check rules validation
            if ($this->validate($rulesValidation)) {
                // for general
                if ($getForm == 'general') {
                    // get value for method post
                    $website_name           = $this->request->getPost('website_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $website_title          = $this->request->getPost('website_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $website_theme_color    = $this->request->getPost('website_theme_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $website_favicon        = $this->request->getFile('website_favicon');
                    $check_website_favicon  = $website_favicon->getFilename();

                    // check website name
                    if ($this->website_name != $website_name) {
                        $execUpdateWebsiteConfig = $this->website_config->set('value', $website_name)->where('name', 'website_name')->update();
                    }

                    // check website title
                    if ($this->website_title != $website_title) {
                        $execUpdateWebsiteConfig = $this->website_config->set('value', $website_title)->where('name', 'website_title')->update();
                    }

                    // check website theme color
                    if ($this->website_theme_color != $website_theme_color) {
                        $execUpdateWebsiteConfig = $this->website_config->set('value', $website_theme_color)->where('name', 'website_theme_color')->update();
                    }

                    // jika ada file yang di upload
                    if (!empty($check_website_favicon)) {
                        $fileName = str_replace(' ', '-', $website_favicon->getName()) . '_' . $website_favicon->getRandomName(); // getFilename()
                        $execUpdateWebsiteConfig = $this->website_config->set('value', 'favicon.ico')->where('name', 'website_favicon')->update();

                        // execute image imanipulation
                        $this->image->withFile($website_favicon)->fit(48, 48, 'center')->save(FCPATH . '/favicon.ico', true);
                        $this->image->withFile($website_favicon)->fit(16, 16, 'center')->save(FCPATH . '/favicon-16x16.png', true);
                        $this->image->withFile($website_favicon)->fit(32, 32, 'center')->save(FCPATH . '/favicon-32x32.png', true);
                        $this->image->withFile($website_favicon)->fit(48, 48, 'center')->save(FCPATH . '/apple-touch-icon.png', true);
                        $this->image->withFile($website_favicon)->fit(512, 512, 'center')->save(FCPATH . '/android-chrome-512x512.png', true);
                        $this->image->withFile($website_favicon)->fit(192, 192, 'center')->save(FCPATH . '/android-chrome-192x192.png', true);
                        $this->image->withFile($website_favicon)->fit(500, 500, 'center')->save(FCPATH . '/assets/images/logo.png', true); // logo
                        // upload real image
                        $website_favicon->move(WRITEPATH . '/uploads/images/' . $fileName);
                    }
                }

                // for seo
                if ($getForm == 'seo') {
                    // get value for method post
                    $website_description    = $this->request->getPost('website_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $website_keywords       = $this->request->getPost('website_keywords', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $website_image          = $this->request->getFile('website_image');
                    $check_website_image    = $website_image->getFilename();

                    if ($this->website_description != $website_description) {
                        $execUpdateWebsiteConfig = $this->website_config->set('value', $website_description)->where('name', 'website_description')->update();
                    }
                    if ($this->website_keywords != $website_keywords) {
                        $execUpdateWebsiteConfig = $this->website_config->set('value', $website_keywords)->where('name', 'website_keywords')->update();
                    }
                    if (!empty($check_website_image)) {
                        $fileName = str_replace(' ', '-', $website_image->getName()) . '_' . $website_image->getRandomName(); // getFilename()
                        $execUpdateWebsiteConfig = $this->website_config->set('value', 'og-image.png')->where('name', 'website_image')->update();

                        // image manipulation
                        $this->image->withFile($website_image)->fit(1200, 630, 'center')->save(FCPATH . '/og-image.png', true);
                        // upload real image
                        $website_image->move(WRITEPATH . '/uploads/images/' . $fileName);
                    }
                }

                // check update data
                if (isset($execUpdateWebsiteConfig)) {
                    // create log data and inserting log
                    $insertLogData = [
                        'username'      => session()->get('username'),
                        'action'        => 'UPDATE WEBSITE CONFIGURATION',
                        'description'   => 'Have successfully updated website configuration for ' . $getForm,
                        'url'           => base_url('/admin/website-settings'),
                        'level'         => 4,
                        'ip_address'    => $this->request->getIPAddress(),
                        'user_agent'    => $this->request->getUserAgent()
                    ];
                    $execInsertLog = $this->log->insert($insertLogData);

                    if (isset($execInsertLog)) {
                        $response['status'] = true;
                        $response['message'] = "Website configuration has been successfully updated!";
                    } else {
                        $response['status'] = false;
                        $response['message'] = 'Failed to insert log, Please contact Developer!';
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = "No changes!";
                }
            } else {
                $response['status'] = false;
                $response['message'] = $this->validation->getErrors();
            }

            // output json
            return $this->response->setJSON($response);
        } else {
            // if direct access return to homepage
            return redirect()->to(base_url());
        }
    }
}
