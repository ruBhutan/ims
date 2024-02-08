<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AccountGroup\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use AccountGroup\Form\CreateAccountGroupForm;




class AccountGroupController extends AbstractActionController
{
     public function createaccountgroupAction()
    {
        $form = new CreateAccountGroupForm();
        return array('form' => $form);
    }
    
}
