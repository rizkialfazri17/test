<?php

namespace App\Controllers\Backend;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\WebsiteConfigurationModel;
use App\Models\AdminsModel;
use App\Models\AdminPermissionsModel;
use App\Models\ProfileAccountModel;
use App\Models\LogsModel;
use App\Models\VisitorsModel;
use App\Models\PostModel;
use App\Models\PostCategoryModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url', 'form', 'file', 'filesystem', 'cookie', 'security', 'global_helper'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->db                   = \Config\Database::connect();
        $this->session              = \Config\Services::session();
        $this->validation           = \Config\Services::validation();
        $this->pager                = \Config\Services::pager();
        $this->image                = \Config\Services::image();
        $this->language             = \Config\Services::language();

        // set language
        $this->language->setLocale($this->session->lang);

        // Decleare models
        $this->website_config       = new WebsiteConfigurationModel;
        $this->admin                = new AdminsModel;
        $this->admin_permissions    = new AdminPermissionsModel;
        $this->profile_account      = new ProfileAccountModel;
        $this->log                  = new LogsModel;
        $this->visitors             = new VisitorsModel;
        $this->post                 = new PostModel;
        $this->post_category        = new PostCategoryModel;

        // Base Website Config
        $this->website_name         = $this->website_config->getValue('website_name');
        $this->website_title        = $this->website_config->getValue('website_title');
        $this->website_theme_color  = $this->website_config->getValue('website_theme_color');
        $this->website_description  = $this->website_config->getValue('website_description');
        $this->website_keywords     = $this->website_config->getValue('website_keywords');
        $this->website_image        = $this->website_config->getValue('website_image');
    }
}
