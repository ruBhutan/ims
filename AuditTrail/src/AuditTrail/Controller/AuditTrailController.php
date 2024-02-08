<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuditTrail\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AuditTrail\Service\AuditTrailServiceInterface;

/**
 * Description of AuditTrailController
 *
 * @author Mendrel
 */
class AuditTrailController extends AbstractActionController {

    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;

    public function __construct(AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->auditTrailService = $auditTrailService;

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];  
        $this->usertype = $authPlugin['user_type_id'];
    }

    public function addAuditTrailAction() {
        try { echo $this->username; die();
            $this->auditTrailService->saveAuditTrail("Login", "Login");
           // $this->auditTrailService->saveLastLogin($this->username);
            exit("AuditTrail Added");
            $this->redirect()->toRoute('registrantemploymentrecord');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }


    public function addLastLoginAction() 
    {
        try {
            $this->auditTrailService->saveLastLogin($this->username);
            exit("Last Login Added");
            $this->redirect()->toRoute('index');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

}
