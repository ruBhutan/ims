<?php

namespace EmployeeDetail\Form;

use Zend\Form\Form;

class AddEmpWorkExperienceForm extends Form
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
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Emp ID',
                                ),
				));
                
                $this->add(array(
				'name' => 'emp_first_name',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'First Name',
                                ),
                    
				));
                $this->add(array(
				'name' => 'emp_midd_name',
				'type' => 'Text',
                                'options' => array(
                                        'class' => 'control-label',
                                ),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Middle Name',
                                ),
				));
                $this->add(array(
				'name' => 'emp_last_name',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Last Name',
                                ),
				));      
                $this->add(array(
				'name' => 'emp_job_title',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Job Title',
                                ),
				));
                $this->add(array(
				'name' => 'emp_work_experience',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Work Experience',
                                        'rows' => 5,
                                ),
				));
                $this->add(array(
				'name' => 'emp_experience_yr',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Year',
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
					'value' => 'Reset',
					'id' => 'resetbutton',
                                        'class' => 'btn btn-default'
					),
				));
                
	}
}