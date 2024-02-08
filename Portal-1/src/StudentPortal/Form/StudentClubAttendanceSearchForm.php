<?php

namespace StudentPortal\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class StudentClubAttendanceSearchForm extends Form
{
	public function __construct()
	{

		// we want to ignore the name passed
        parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
			'name' => 'year',
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					'placeholder' => 'Enter Year',
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