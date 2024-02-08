<?php

namespace StudentAttendance\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CancelledLecturesForm extends Form
{
	protected $timetable_dates;
	
	public function __construct($timetable_dates)
     {
        parent::__construct('cancelledlectures');
		
		$this->timetable_dates = $timetable_dates;
		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        		
        $this->setAttributes(array(
            'class' => 'radio',
        ));
		
		$this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'programme_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'academic_modules_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'from_date',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'to_date',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'section',
              'type' => 'Hidden'  
         ));
		
		foreach($this->timetable_dates as $key=>$value)
		{
			$this->add(array(
		   'name' => 'lectures_'.$key,
		   'type'=> 'checkbox',
		   'options' => array(
				 'class' => 'flat',
				 'use_hidden_element' => true,
				 'checked_value' => 'cancel',
				 'unchecked_value' => 'uncancel'
			 ),
			'attributes' => array(
				'value' => 'uncancel',
			),
		 ));
		}

		foreach($this->timetable_dates as $key=>$value)
		{
			$this->add(array(
				'name' => 'reasons_'.$key,
				'type' => 'textarea',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					'rows' => 2
					),
			));
		}
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 1200
                )
             )
         ));

         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Cancelled Lectures',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
	 
}