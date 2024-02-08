<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ParentDashboard\Controller;



use Zend\Mvc\Controller\AbstractActionController;
//use EmpDashboard\Form\EmpDashboardForm;
use Zend\View\Model\ViewModel;




class ParentDashboardController extends AbstractActionController
{
     public function parentdashboardAction()
     {
         return new ViewModel();
     }
     public function parentnotificationAction()
     {
         return new ViewModel();
     }
     public function moduletutorAction()
     {
         return new ViewModel();
     }
    
}
