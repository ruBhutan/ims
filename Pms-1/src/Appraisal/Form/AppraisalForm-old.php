<?php

namespace Appraisal\Form;

use Zend\Form\Form;

class AppraisalForm extends Form
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
           'name' => 'department_name',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '--Select --',
                         
                    // '3' => 'Male'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>'1'//set selected to '1'
             ),
         ));
          
          $this->add(array(
           'name' => 'position_title',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '--Select --',
                   
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
          $this->add(array(
           'name' => 'position_category',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '--Select --',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
          $this->add(array(
           'name' => 'position_group',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '--Select --',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
          
           $this->add(array(
           'name' => 'emp_existing_no',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'placeholder' =>'1',
             ),
         ));
           
           $this->add(array(
           'name' => 'emp_requirement_no',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
           $this->add(array(
           'name' => 'course_title',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
           $this->add(array(
           'name' => 'no_of_slots',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
            $this->add(array(
           'name' => 'duration',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
             $this->add(array(
           'name' => 'training_type',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
              $this->add(array(
           'name' => 'funding_source',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
          
            
          
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Save',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
					),
				
				));
     }
}