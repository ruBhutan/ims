<?php

namespace AcademicAssessment\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class StudentRepeatSearchForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
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
				'required' => true,
				),
		));
                
        $this->add(array(
			'name' => 'semester',
				'type'=> 'select',
					'options' => array(
						'empty_option' => 'Please Select a Semester',
						'disable_inarray_validator' => true,
						'class'=>'control-label',
					),
					'attributes' => array(
							'class' => 'form-control ',
							'required' => true,
					),
				));

				/*$this->add(array(
					'name' => 'marks',
					'type' => 'text',
					'options' => array(
						'class' => 'control-label',
						),
						'attributes' =>array(
							'class' => 'form-control',
							'min' => 0.0,
							'step' => 0.01,
							'required' => false,
						),
					
				));*/

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