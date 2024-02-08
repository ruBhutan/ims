<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace VoucherMaster\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use VoucherMaster\Form\CreateVoucherMasterForm;




class VoucherMasterController extends AbstractActionController
{
     public function createvouchermasterAction()
    {
        $form = new CreateVoucherMasterForm();
        return array('form' => $form);
    }
    
}
