<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FeeImport\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use FeeImport\Form\FeeImportForm;




class FeeImportController extends AbstractActionController
{
     public function feeimportAction()
    {
        $form = new FeeImportForm();
        return array('form' => $form);
    }
    
}