<?php

namespace AcademicCalendar\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class StaffCalendarEventForm extends Form
{
	public function __construct()
	{
		  // we want to ignore the name passed
        parent::__construct('staffcalendar');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
		
		$this->add(array(
             'type' => 'AcademicCalendar\Form\StaffCalendarEventFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));
		
		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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