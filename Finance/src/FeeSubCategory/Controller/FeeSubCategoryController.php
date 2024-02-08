<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FeeSubCategory\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use FeeSubCategory\Form\AddFeeSubCategoryForm;




class FeeSubCategoryController extends AbstractActionController
{
     public function addfeesubcategoryAction()
    {
        $form = new AddFeeSubCategoryForm();
        return array('form' => $form);
    }
    
}