<?php

namespace ExtraCurricularAttendance\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class ExtraCurricularAttendanceForm extends Form
{
	protected $studentCount;
	
	public function __construct($studentCount)
     {
        parent::__construct('attendance');
		
		$this->studentCount = $studentCount;
	
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'programme',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'year',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'student_name',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'student_count',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'clubs_id',
              'type' => 'Hidden'  
         ));
                     
         $this->add(array(
           'name' => 'date',
            'type'=> 'date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'social_events_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'student_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 		 
		 for($i=1; $i <= $this->studentCount; $i++)
		 {
			$this->add(array(
			   'name' => 'attendance_'.$i,
			   'type'=> 'Radio',
			   'options' => array(
					 'class' => 'flat',
					 'value_options' => array(
						'Present' => 'Present',
						'Absent' => 'Absent',
					 )
				 ),
               'attributes' => array(
                    'required' => true
            ),
		  ));
		 }
		 
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
					'value' => 'Submit Attendance',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
}