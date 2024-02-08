<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace VoucherHead\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use VoucherHead\Form\CreateVoucherHeadForm;
use Zend\View\Model\ViewModel;




class VoucherHeadController extends AbstractActionController
{
     public function createvoucherheadAction()
    {
        $form = new CreateVoucherHeadForm();
        return array('form' => $form);
    }
    public function viewvoucherheadAction()
    {
        return new ViewModel();
    }
}