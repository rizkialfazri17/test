<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Backend
$routes->get('/admin/signin', 'Backend\Signin::index');
$routes->post('/admin/signin', 'Backend\Signin::signin');
$routes->get('{locale}/admin/signin', 'Backend\Signin::index');
$routes->post('{locale}/admin/signin', 'Backend\Signin::signin');

$routes->group('{locale}/admin', ['filter' => 'admin_auth'], function ($routes) {
    $routes->get('', 'Backend\Dashboard::index');
    $routes->get('dashboard', 'Backend\Dashboard::index');
    $routes->post('dashboard', 'Backend\Dashboard::table_visitors');

    $routes->get('post', 'Backend\Staff\Post::index');
    $routes->post('post', 'Backend\Staff\Post::table');
    $routes->get('post/add', 'Backend\Staff\Post::add');
    $routes->add('post/save', 'Backend\Staff\Post::save');
    $routes->get('post/edit/(:any)', 'Backend\Staff\Post::edit/$1');
    $routes->add('post/update', 'Backend\Staff\Post::update');
    $routes->add('post/delete', 'Backend\Staff\Post::delete');
    $routes->add('post/table-category', 'Backend\Staff\Post::table_category');
    $routes->add('post/add-category', 'Backend\Staff\Post::add_category');
    $routes->add('post/delete-category', 'Backend\Staff\Post::delete_category');

    $routes->get('manage-admins', 'Backend\Superadmin\ManageAdmins::index');
    $routes->add('manage-admins/table', 'Backend\Superadmin\ManageAdmins::table');
    $routes->add('manage-admins/add', 'Backend\Superadmin\ManageAdmins::add');
    $routes->add('manage-admins/edit', 'Backend\Superadmin\ManageAdmins::edit');
    $routes->get('manage-admins/edit/(:any)', 'Backend\Superadmin\ManageAdmins::edit/$1');
    $routes->add('manage-admins/update', 'Backend\Superadmin\ManageAdmins::update');
    $routes->add('manage-admins/view/(:any)', 'Backend\Superadmin\ManageAdmins::view/$1');
    $routes->add('manage-admins/delete', 'Backend\Superadmin\ManageAdmins::delete');

    $routes->add('manage-admins/edit-permissions', 'Backend\Superadmin\ManageAdmins::edit_permissions');
    $routes->get('manage-admins/edit-permissions/(:any)', 'Backend\Superadmin\ManageAdmins::edit_permissions/$1');
    $routes->add('manage-admins/update-permissions', 'Backend\Superadmin\ManageAdmins::update_permissions');

    $routes->get('website-settings', 'Backend\Superadmin\WebsiteSettings::index');
    $routes->add('website-settings/update', 'Backend\Superadmin\WebsiteSettings::update');

    $routes->get('logs', 'Backend\Superadmin\Logs::index');
    $routes->add('logs/table', 'Backend\Superadmin\Logs::table');

    $routes->get('my-profile', 'Backend\MyProfile::index');
    $routes->add('my-profile/update', 'Backend\MyProfile::update');
    $routes->add('my-profile/change-password', 'Backend\MyProfile::change_password');
	
	$routes->get('account-settings', 'Backend\AccountSettings::index');

    $routes->get('my-history', 'Backend\MyHistory::index');
    $routes->post('my-history', 'Backend\MyHistory::table');

    $routes->add('signout', 'Backend\Signout::index');
	
	$routes->post('ajax/state', 'Backend\Ajax::state');
	$routes->post('ajax/city', 'Backend\Ajax::city');
});

// other
$routes->add('/upload-image-handler', 'Backend\ImageHandler::uploadImage');
$routes->add('/delete-image-handler', 'Backend\ImageHandler::deleteImage');

$routes->get('/sitemap\.xml', 'Sitemap::index');
