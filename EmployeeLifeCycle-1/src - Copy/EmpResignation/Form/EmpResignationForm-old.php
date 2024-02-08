<?php

namespace EmpResignation\Form;

use Zend\Form\Form;

class EmpResignationForm extends Form
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
           'name' => 'emp_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                      '1' => '--Select--',
                     '2' => 'RUB201',
                     '3' => 'RUB202',
                    
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          $this->add(array(
           'name' => 'resignation_type',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                      '1' => '--Select--',
                     '2' => 'Voluntary',
                    
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>'1'//set selected to '1'
             ),
         ));
         
      
            $this->add(array(
     
              'name' => 'resignation_date',
                'type' => 'date',
             'options' => array(
               'class' => 'control-label',  
             ),
             'attributes' => array(
                 'class' => 'form-control'
              ),   
             
         ));
            
          $this->add(array(
             'name' => 'reasons',
               'type'=>'textarea',
             'options' => array(
               'class' => 'control-label',  
             ),
             'attributes' => array(
                 'class' => 'form-control',
               
                 'rows'=>'3',
             ),
         ));
        
         $this->add(array(
             'name' => 'upload',
              'type' => 'file',
              'options' =>array(
                  'class'=>'control-label',
                   ),
             'attributes' => array(
            
                 'class' => 'form-control',
                 'value' => 'Choose File',
              ),   
            
         ));  
            
             $this->add(array(
				'name' => 'separation_order_no',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
                    
				));
                $this->add(array(
				'name' => 'separation_order_date',
				'type' => 'Date',
                                'options' => array(
                                        'class' => 'control-label',
                                ),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                $this->add(array(
				'name' => 'separation_category',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                     '2' => 'Superannuation',
                     '3' => 'Voluntary Resignation',
                     '4' => 'Compulsory Resignation',
                                        ),
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));      
              
                $this->add(array(
				'name' => 'remarks',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
         
       
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Submit',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
					),
				
				));
          $this->add(array(
				'name' => 'reset',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Reset',
					'id' => 'submitbutton',
                                        'class' => 'btn btn-default'
					),
				));
              
     }
}