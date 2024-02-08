<?php

namespace Job\Form;

use Zend\Form\Form;

class JobForm extends Form
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
           'name' => 'grade',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                      '1'=>'--select--',     
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           $this->add(array(
           'name' => 'currency',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                       
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
            $this->add(array(
           'name' => 'minimum_salary',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
               $this->add(array(
           'name' => 'maximum_salary',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        
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
					'value' => 'Save',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
					),
				
				));
         
         
          $this->add(array(
           'name' => 'job_title',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           $this->add(array(
           'name' => 'job_description',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
            $this->add(array(
           'name' => 'job_specification',
            'type'=> 'File',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
               $this->add(array(
           'name' => 'notes',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
              $this->add(array(
           'name' => 'employment_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
              
                $this->add(array(
           'name' => 'job_category',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
     }
}