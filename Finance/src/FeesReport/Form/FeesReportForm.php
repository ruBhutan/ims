<?php

namespace FeesReport\Form;

use Zend\Form\Form;

class FeesReportForm extends Form
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct();
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
          $this->add(array(
           'name' => 'student_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                          '1' => '--student ID here--',
                          
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           $this->add(array(
           'name' => 'report',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                            '1' => '--Select--',
                          '2' => 'Paid Reports',
                          '3' => 'Unpaid Reports',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));

             $this->add(array(
           'name' => 'course',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                          '1' => '--select course--',
                          
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           
             $this->add(array(
           'name' => 'semester',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                          '1' => '--Select semester--',
                          
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Print',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-danger',
					),
				
				));
     }
}