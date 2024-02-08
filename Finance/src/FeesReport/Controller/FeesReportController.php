<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FeesReport\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use FeesReport\Form\FeesReportForm;




class FeesReportController extends AbstractActionController
{
     public function feesreportAction()
    {
        $form = new FeesReportForm();
        return array('form' => $form);
    }
    
}