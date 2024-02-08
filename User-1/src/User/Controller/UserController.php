<?php

/**
 * @author Samier Sompura <samier.sompura@wamasoftware.com>
 * @link http://www.wamasoftware.com
 */

namespace User\Controller;

use Exception;
use Zend\File\Transfer\Adapter\Http;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * User Controller
 */
class UserController extends AbstractActionController
{
    protected $serviceLocator;
	
	public function __construct($serviceLocator)
	{
            $this->serviceLocator = $serviceLocator;
	}
    /**
     * User welcome Page
     * URL : /user/welcome
     */
    public function welcomeAction()
    {
        return new ViewModel();
    }
}
