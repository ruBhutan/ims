<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FeeCollection\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use FeeCollection\Form\FeeCollectionForm;




class FeeCollectionController extends AbstractActionController
{
     public function feecollectionAction()
    {
        $form = new FeeCollectionForm();
        return array('form' => $form);
    }
    
}