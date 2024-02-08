<?php

/**
 * @author eDruk <edruk@edruk.com.bt>
 * @link http://web.edruk.com.bt
 */

namespace Acl\Controller;

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
class AclController extends AbstractActionController
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
    public function indexAction()
    {
        return new ViewModel();
    }
    public function unauthorizedAction()
    {
        $flashMessenger = $this->flashMessenger()->setNamespace('error');
        $flashMessenger->addMessage('You are not authorized to access this resource.');
        $view = new ViewModel();
        $view->setTemplate('application/index/index');
        return $view;
    }
}
