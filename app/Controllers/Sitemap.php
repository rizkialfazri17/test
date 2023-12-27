<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Sitemap extends BaseController
{
    // constructor
    // function __construct()
    // {
    //     $this->homepage     = new HomepageModel();
    //     $this->apa_itu      = new ApaItuModel();
    //     $this->testimony    = new TestimonyModel();
    //     $this->questions    = new QuestionsModel();
    //     $this->post         = new PostModel();
    // }

    // // index
    // public function index(): string
    // {
    //     $data['homepage']   = $this->homepage->orderBy('update_time', 'desc')->limit(1)->first();
    //     $data['apa_itu']    = $this->apa_itu->orderBy('updated_time', 'desc')->limit(1)->first();
    //     $data['testimony']  = $this->testimony->orderBy('created_time', 'desc')->limit(1)->first();
    //     $data['questions']  = $this->questions->orderBy('created_time', 'desc')->limit(1)->first();
    //     $data['post']       = $this->post->orderBy('created_time', 'desc')->limit(1)->first();
    //     $data['postList']   = $this->post->select('*')->where('status', 1)->orderBy('created_time', 'asc')->findAll();

    //     // load layout and get the page
    //     return view('Sitemap', $data);
    // }
}
