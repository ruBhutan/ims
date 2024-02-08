<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CreateVoucher\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use CreateVoucher\Form\CreateVoucherForm;
use Zend\View\Model\ViewModel;




class CreateVoucherController extends AbstractActionController
{
     public function createvoucherAction()
    {
        $form = new CreateVoucherForm();
        return array('form' => $form);
    }
    public function viewvoucherAction()
    {
        return new ViewModel();
    }
}