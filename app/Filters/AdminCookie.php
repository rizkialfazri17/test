<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminCookie implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in') && is_null(session()->get('logged_in')) && empty(session()->get('logged_in')) && !session()->get('username') && is_null(session()->get('username')) && empty(session()->get('username')) && isset($_COOKIE['admin_auth'])) {

            // load helper
            helper('global_helper');

            // decode cookie
            $decrypt_cookie = AESHash('decrypt', $_COOKIE['admin_auth'], getenv('encryption.key'));

            // validate hash
            if (!$decrypt_cookie || $decrypt_cookie == false || $decrypt_cookie == 'false') {
                setcookie('admin_auth', null, -1);
            } else {
                $old_session = json_decode($decrypt_cookie, true);
                session()->set($old_session);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
