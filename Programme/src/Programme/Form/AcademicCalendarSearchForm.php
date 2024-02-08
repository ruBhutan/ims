<?php

namespace Programme\Form;

use Zend\Form\Form;

class AcademicCalendarSearchForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
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
	
	public function getInputFilterSpecification()
     {
         return array(
             'programmes_id' => array(
                 'required' => true,
             ),
			 'academic_year' => array(
                 'required' => true,
             ),
         );
     }
}