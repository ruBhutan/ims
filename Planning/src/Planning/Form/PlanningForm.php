<?php

namespace Planning\Form;

use Zend\Form\Form;

class PlanningForm extends Form
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
           'name' => 'mission',
            'type'=> 'TextArea',
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
           'name' => 'weight',
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
           'name' => 'five_year_plan',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '12th Five Year Plan',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
          $this->add(array(
           'name' => 'financial_year',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '2016 to 2017',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
          $this->add(array(
           'name' => 'administrative_unit',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '5',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
           $this->add(array(
           'name' => 'department',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => 'ICT',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
          $this->add(array(
           'name' => 'plan_type',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => 'A',
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          $this->add(array(
           'name' => 'autoretrieve',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     
                     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'placeholder' => 'Auto Retrieve',
             ),
         ));
           $this->add(array(
           'name' => 'character_evaluator',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => '--Select--',
                      '2' => 'RUB01-Dorjii-DSA',
                     '3' => 'RUB02-Dorjii-Provost1',
                  '4' => 'RUB01-Dorjii-Programme-Leader',
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
           $this->add(array(
				'name' => 'cancel',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Cancel',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-danger',
					),
				
				));
     }
}