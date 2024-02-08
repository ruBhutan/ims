<?php

namespace StaffPortal\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class StaffAttendanceForm extends Form
{
	protected $start_date;
	protected $end_date;
	protected $no_days;
	//protected $day;
	
	public function __construct($start_date, $end_date)
     {
        parent::__construct('empattendance');
		
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		
		$this->no_days = $this->getNoDays($this->start_date, $this->end_date);
		//$this->day = (int) substr($this->start_date,8,2);

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
             'name' => 'departments_units_id',
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

			for($j= 0; $j<$this->no_days; $j++)
			{
				$this->add(array(
			   'name' => 'attendance_'.$j,
			   'type'=> 'checkbox',
			   'options' => array(
					 'class' => 'flat',
					 'use_hidden_element' => true,
					 'checked_value' => 'absent',
					 'unchecked_value' => 'present'
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
					'value' => 'Submit Evaluation',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
	 
	 public function getNoDays($from_date, $to_date)
	 {
		$from_date = strtotime($from_date);
		$to_date =strtotime($to_date);
		$date_diff = $to_date-$from_date;
		$no_days = floor($date_diff/(60*60*24)) + 1;
		return $no_days;
	 }
	 
}