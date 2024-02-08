<?php

namespace CharacterCertificate\Form;

use Zend\Form\Form;

class SearchForm extends Form
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
		//need to change the drop down and retrieve from database
		$this->add(array(
			'name' => 'academic_module_tutors_id',
			'type' => 'select',
			'options' => array(
                 'empty_option' => 'Please Select a Module',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Module',
				'required' => true,
				),
		));
		
		$this->add(array(
			'name' => 'programmes_id',
			'type' => 'select',
			'options' => array(
                 'empty_option' => 'Please Select a Programme',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Programme',
				'required' => true,
				),
		));
		
		$this->add(array(
			'name' => 'batch',
			'type' => 'select',
			'options' => array(
                 'empty_option' => 'Please Select a Batch',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Batch',
				),
		));
						
		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 1800
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