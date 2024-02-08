<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FeeAllocation\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use FeeAllocation\Form\AddFeeAllocationForm;




class FeeAllocationController extends AbstractActionController
{
     public function addfeeallocationAction()
    {
        $form = new AddFeeAllocationForm();
        return array('form' => $form);
    }
    
}