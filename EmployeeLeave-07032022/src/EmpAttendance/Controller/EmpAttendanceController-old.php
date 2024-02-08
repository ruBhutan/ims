<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmpAttendance\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use EmpAttendance\Form\EmpAttendanceForm;




class EmpAttendanceController extends AbstractActionController
{
     public function empattendanceAction()
    {
        $form = new EmpAttendanceForm();
        return array('form' => $form);
    }
    
}
