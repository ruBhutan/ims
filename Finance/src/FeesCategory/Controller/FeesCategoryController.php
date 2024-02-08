<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FeesCategory\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use FeesCategory\Form\AddFeesCategoryForm;




class FeesCategoryController extends AbstractActionController
{
     public function addfeescategoryAction()
    {
        $form = new AddFeesCategoryForm();
        return array('form' => $form);
    }
    
}