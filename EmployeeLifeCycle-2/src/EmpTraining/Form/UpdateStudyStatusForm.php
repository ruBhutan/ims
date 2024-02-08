<?php

namespace EmpTraining\Form;

use Zend\Form\Form;

class UpdateStudyStatusForm extends Form
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
				'type' => 'Hidden',
				));
             
                $this->add(array(
				'name' => 'emp_id',
				'type' => 'Select',
                              'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                      '2' => 'RUB100', 
                     '3' => 'RUB200',                
                                        ),
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                  
                                ),
                    
				));
                
             $this->add(array(
				'name' => 'aggregate_marks_obtained',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					
                                    ),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                  $this->add(array(
				'name' => 'study_status',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
				'value_options' => array(
                     '1' => 'Under Going',
                     '2' => 'Completed', 
                     '3' => 'Extension', 
                                    ),	
                                    ),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                  
                   $this->add(array(
				'name' => 'training_type',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                                        '2' => 'Seminar',
                                        '3' => 'Workshop',
                                        '4' => 'Conference',
                                        '5' => 'Symposium',
                                        '6' => 'Fieldtrip Training',
                                        '7' => 'Study Visit',
                                        
                                        
                                        
                                        ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                   $this->add(array(
				'name' => 'training_category',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                    '2' => 'Long Term Professional Development',
                     '3' => 'Short Term Professional Development',
                     '4' => 'Work Place Based Professional Development',
                    '5' => 'Secondment',
                    '6' => 'Job Exchange',
                ),
			),
                'attributes' =>array(
                    'class' => 'form-control',
                ),
				)); 
                     $this->add(array(
				    'name' => 'date_of_extension',
				    'type' => 'Date',
				    'options' => array(
					'class' => 'control-label',
                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                    'class' => 'form-control',
                                        
                ),
				));
                       $this->add(array(
				'name' => 'upload',
				'type' => 'File',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
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
                                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                    'class' => 'form-control',
                    ),
				));
               
		      $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Save',
					'id' => 'submitbutton',
                    'class' => 'btn btn-success'
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