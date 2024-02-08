<?php

namespace Vacancy\Form;

use Zend\Form\Form;

class AddJobVacancyForm extends Form
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
				'name' => 'position_title',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                      'placeholder' => 'Finance Officer',
                                ),
                     
                    
				));
                 $this->add(array(
				'name' => 'position_category',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                       'placeholder' => 'Administrative',
                                ),
                    
				));
                  $this->add(array(
				'name' => 'position_level',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                       'placeholder' => 'P5',
                                ),
                    
				));
                $this->add(array(
				'name' => 'vacancy_slot',
				'type' => 'Text',
                                'options' => array(
                                        'class' => 'control-label',
                                ),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => '1',
                                ),
				));
                $this->add(array(
				'name' => 'institute_name',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                                     
                                 ),
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                         'placeholder' => 'Sherubtse College',
                                ),
				));      
              
                $this->add(array(
				'name' => 'job_requirement',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                        'placeholder'=>'Auto Retrieve and Editable ',
                                        
                                ),
				));
                 $this->add(array(
				'name' => 'education',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                $this->add(array(
				'name' => 'submission_date',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                  $this->add(array(
				'name' => 'advertisement_date',
				'type' => 'Date',
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
					'value' => 'Submit',
					'id' => 'submitbutton',
                                        'class' => 'btn btn-success'
					),
				));
                $this->add(array(
				'name' => 'reset',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Cancel',
					'id' => 'resetbutton',
                                        'class' => 'btn btn-danger'
					),
				));
                
	}
}