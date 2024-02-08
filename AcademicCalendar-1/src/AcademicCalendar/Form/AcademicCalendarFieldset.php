<?php

namespace AcademicCalendar\Form;

use AcademicCalendar\Model\AcademicCalendar;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AcademicCalendarFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('academiccalendar');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AcademicCalendar());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
				'name' => 'employee_details_id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
			'name' => 'academic_event',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Academic Event',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'academic_year',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select an Academic Year',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
              
		$this->add(array(
			'name' => 'date_range',
			'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
				  'id' => 'reservation'
             ),
         ));
		 
		 $this->add(array(
			'name' => 'event_for',
			'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Select Event For:',
                 'value_options' => array(
                      'Staff' => 'For Staff',
					  'Student' => 'For Student',
					  'All' => 'All',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		
		$this->add(array(
			'name' => 'remarks',
			'type' => 'Textarea',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'rows' => 3
				),
		));
		            
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success',
				),
			));
		
     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'name' => array(
                 'required' => false,
             ),
         );
     }
}