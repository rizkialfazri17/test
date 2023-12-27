<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\WebsiteConfigurationModel;

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
    protected $helpers = ['url', 'form', 'file', 'cookie', 'global', 'filesystem', 'security'];

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
        $this->db           = \Config\Database::connect();
        $this->session      = \Config\Services::session();
        $this->validation   = \Config\Services::validation();
        $this->pager        = \Config\Services::pager();

        // // Decleare models
        $this->website_config       = new WebsiteConfigurationModel;

        // // Base Website Config
        $this->website_name         = $this->website_config->getValue('website_name');
        $this->website_title        = $this->website_config->getValue('website_title');
        $this->website_description  = $this->website_config->getValue('website_description');
        $this->website_keywords     = $this->website_config->getValue('website_keywords');
        $this->website_image        = $this->website_config->getValue('website_image');
    }
}
