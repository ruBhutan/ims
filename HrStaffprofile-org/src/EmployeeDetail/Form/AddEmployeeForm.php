<?php

namespace EmployeeDetail\Form;

use Zend\Form\Form;

class AddEmployeeForm extends Form
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
                                        'placeholder' => 'Rub001',
                                ),
				));
                
                $this->add(array(
				'name' => 'emp_first_name',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => '--Enter FirstName --',
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
                                        'placeholder' => '--Enter MiddleName-- ',
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
                                        'placeholder' => '--Enter LastName--',
                                ),
				));
               
                 $this->add(array(
				'name' => 'cid',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Citizen Identity ship card',
                                ),
				));
                 $this->add(array(
				'name' => 'nationality',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' =>' Nationality',
                                ),
				));
		
		
		
                $this->add(array(
				'name' => 'date_of_birth',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'DOB',
                                ),
				));
                  $this->add(array(
				'name' => 'emp_house_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'House No.',
                                ),
				));
                    $this->add(array(
				'name' => 'emp_thram_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Thram No.',
                                ),
				));
                
                $this->add(array(
				'name' => 'emp_dzongkhag',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Dzongkhag',
                                ),
				));
                $this->add(array(
				'name' => 'emp_gewog',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Geog',
                                ),
				));
                $this->add(array(
				'name' => 'emp_village',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Village',
                                ),
				));
                
                $this->add(array(
				'name' => 'emp_category',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Category',
                                ),
				));
                  $this->add(array(
				'name' => 'gender',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Gender',
                                ),
				));
                    $this->add(array(
				'name' => 'marital_status',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Marital Status',
                                ),
				));
                    
                     $this->add(array(
				'name' => 'photograph',
				'type' => 'File',
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
					'value' => 'Reset',
					'id' => 'resetbutton',
                                        'class' => 'btn btn-default'
					),
				));
                
	}
}