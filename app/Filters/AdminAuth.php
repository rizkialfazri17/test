<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // check session, jika user belum login
        if (!session()->get('logged_in') || is_null(session()->get('logged_in')) || empty(session()->get('logged_in')) || !session()->get('username') || is_null(session()->get('username')) || empty(session()->get('username'))) {
            // maka redirect ke halaman login
            session()->setFlashdata('failed_login', lang('Validation.please_login_first'));
            return redirect()->to(base_url('/admin/signin'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
