<?php

/**
 * @author eDruk ICT <edruk@edruk.com.bt>
 * @link http://web.edruk.com.bt
 */
 
namespace Auth\Controller\Plugin;

use DateTime;
use DateTimeZone;
use DOMDocument;
use Zend\Feed\Reader\Reader as Feed;
use Zend\Http\Client;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Session\Container;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *  Auth Plugin
 */
class AuthPlugin extends AbstractPlugin implements ServiceManagerAwareInterface
{

    private $sm;

    /**
     * @var Payload Data
     */
    protected $payloadData = null;
    
    protected $serviceLocator;
	
    public function __construct($serviceLocator) {
            $this->serviceLocator = $serviceLocator;
    }
    public function logout()
    {
        $this->getServiceManager()->get('Zend\Authentication\AuthenticationService')->clearIdentity();
        $sessionPermission = new Container('permission');
        $sessionPermission->getManager()->getStorage()->clear();
        return true;
    }

    /**
     *  isLoggedIn
     */
    public function isLoggedIn()
    {
        try {
            $serviceManage = $this->serviceLocator;
            if (!is_null($serviceManage)) {
                
                $loggedUser = $serviceManage->get('Zend\Authentication\AuthenticationService')->getIdentity();
                if (is_object($loggedUser)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get SSO User Attributes
     */
    public function getUserAttributes()
    {
        if (!$this->isLoggedIn())
            return array();

        $authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');
        $loggedUser = $authService->getIdentity();
        $data['role'] = 'guest';
        $data['id'] = $loggedUser->id;
        $data['username'] = $loggedUser->username;
        $data['role'] = $loggedUser->role;
        $data['user_type_id'] = $loggedUser->user_type_id;
        $data['user_status_id'] = $loggedUser->user_status_id; 
        $data['region'] = $loggedUser->region;
        /*if($loggedUser->getRoles() && count($loggedUser->getRoles()) > 0 && $loggedUser->getRoles()[0]){
            $data['role'] = $loggedUser->getRoles()[0]->getRole();
        } */

        $zendContainer = new Container('permisson');
        $data['premission'] = $zendContainer->offsetGet('permission');
        return $data;
    }

    /**
     *  requireAuth
     */
    public function getUserDetails($userId)
    {
        $userModel = $this->getServiceManager()->get('User\Model\User');
        $data = $userModel->getUserProfile($userId);
        if (count($data) > 0)
            return $data[0];
        return array();
    }


    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;
    }

    public function getServiceManager()
    {
        if ($this->sm == null && $this->getController() != null)
            $this->sm = $this->getController()->getServiceLocator();
        return $this->sm;
    }

}
