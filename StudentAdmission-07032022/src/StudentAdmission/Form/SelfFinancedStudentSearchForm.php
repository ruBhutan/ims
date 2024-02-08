<?php

namespace StudentAdmission\Form;

use Zend\Form\Form;

class SelfFinancedStudentSearchForm extends Form
{
	public function __construct()
	{
                // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
                    'name' => 'student_name',
                    'type' => 'text',
                    'options' => array(
                            'class' => 'control-label',
                            ),
                    'attributes' =>array(
                            'class' => 'form-control',
                            'placeholder' => 'Student Name',
                            ),
		));
		
		$this->add(array(
                    'name' => 'student_id',
                    'type' => 'text',
                    'options' => array(
                            'class' => 'control-label',
                            ),
                    'attributes' =>array(
                            'class' => 'form-control',
                            'placeholder' => 'Student ID',
                            ),
		));

                $this->add(array(
                    'name' => 'organisation_id',
                    'type' => 'select',
                    'options' => array(
                        'empty_option' => 'Please Select Organisation',
                        'disable_inarray_validator' => true,
                        'class' => 'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                        'id' => 'selectSelfFinancedOrganisation',
                        'options' => array(),
                    ),
                ));
                
                $this->add(array(
                    'type' => 'Zend\Form\Element\Csrf',
                    'name' => 'csrf',
                    'options' => array(
                        'csrf_options' => array(
                            'timeout' => 600
                        )
                    )
                ));
                
		$this->add(array(
                    'name' => 'submit',
                    'type' => 'Submit',
                    'attributes' => array(
                            'value' => 'Search',
                            'id' => 'submitbutton',
                            'class' => 'btn btn-success'
                            ),
		));
	}
}