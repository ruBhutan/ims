<?php

namespace StudentAttendance\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class ExtraClassAttendanceForm extends Form
{
	protected $studentList;
	
	public function __construct($studentList)
     {
        parent::__construct('studentattendance');
		
		$this->studentList = $studentList;
		
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
             'name' => 'employee_details_id',
              'type' => 'Hidden'  
         ));
		 
		  $this->add(array(
             'name' => 'year',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'from_date',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'section',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'to_date',
              'type' => 'Hidden'  
         ));

		 $this->add(array(
             'name' => 'from_time',
              'type' => 'Hidden'  
         ));

		foreach($this->studentList as $id=>$name)
		{
			$this->add(array(
			   'name' => 'attendance_'.$id,
			   'type'=> 'checkbox',
			   'options' => array(
					 'class' => 'flat',
					 'use_hidden_element' => true,
					 'checked_value' => 'present',
					 'unchecked_value' => 'absent'
				 ),
				'attributes' => array(
					'value' => 'present',
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
					'value' => 'Submit Attendance',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
	 
}