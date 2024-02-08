<?php

namespace EmpTraining\Form;

use Zend\Form\Form;

class TrainingApprovalForm extends Form
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
				'name' => 'training_order_no',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
                    
				));
                $this->add(array(
				'name' => 'training_order_date',
				'type' => 'Text',
                                'options' => array(
                                        'class' => 'control-label',
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
				'name' => 'course_title',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                     
                                ),
				));
                 $this->add(array(
				'name' => 'course_title_adhoc',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                     
                                ),
				));
                
                 $this->add(array(
				'name' => 'institute_university',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                 
                 $this->add(array(
				'name' => 'training_country',
				'type' => 'Select',
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
				'name' => 'training_city',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'training_start_date',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'training_end_date',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                   $this->add(array(
				'name' => 'source_of_funding',
				'type' => 'Select',
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
				'name' => 'security_clearance_certificate_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'audit_clearance_certificate_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'medical_fitness_certificate_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                  $this->add(array(
				'name' => 'long_term_study_order_no',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
                    
				));
                
                $this->add(array(
				'name' => 'long_term_study_order_date',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
                    
				));
                $this->add(array(
				'name' => 'study_level',
				'type' => 'Text',
                                'options' => array(
                                        'class' => 'control-label',
                                ),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                
                $this->add(array(
				'name' => 'hrd_type',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
				'value_options' => array(
                     '1' => '--select--',
                       '2' => 'Planned',              
                        '3' => 'Ad hoc',             
                                    ),	
                                    
                                    ),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                
                 $this->add(array(
				'name' => 'study_country',
				'type' => 'Select',
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
				'name' => 'study_city',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'study_start_date',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'study_end_date',
				'type' => 'Date',
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
                     '1' => '--select--',),	
                                    ),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                   $this->add(array(
				'name' => 'study_mode',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                     'value_options' => array(
                     '1' => '--select--',
                                          '2' => 'Full Time',
                                          '3' => 'Continue Education',
                                         
                                         ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                   
                    $this->add(array(
				'name' => 'professional_development_no',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                     'value_options' => array(
                     '1' => '--select--',
                     '2' => '1st  Long Term Professional Development',
                     '3' => '2nd  Long Term Professional Development',
                    '4' => '3rd  Long Term Professional Development',
                     ),
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
               $this->add(array(
				'name' => 'programme',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                    'class' => 'form-control',
                    ),
				));
                
	}
}