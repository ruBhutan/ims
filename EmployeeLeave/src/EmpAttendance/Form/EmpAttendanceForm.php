<?php

namespace EmpAttendance\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EmpAttendanceForm extends Form
{
	protected $staffList;
	protected $start_date;
	protected $end_date;
	protected $no_days;
	protected $day;
	
	public function __construct($staffList, $start_date, $end_date)
     {
        parent::__construct('empattendance');
		
		$this->staffList = $staffList;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		
		$this->no_days = $this->getNoDays($this->start_date, $this->end_date);
		$this->day = (int) substr($this->start_date,8,2);
		$staffs = array();
		$i=1;
		 foreach($this->staffList as $detail){
			 $staffs[$i++] = $detail['id'];
		 }

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

		for($i=1; $i <= count($this->staffList); $i++)
		{
			for($j= $this->day; $j<($this->day+$this->no_days); $j++)
			{
				$this->add(array(
			   'name' => 'attendance_'.$i.'_'.$j,
			   'type'=> 'checkbox',
			   'options' => array(
					 'class' => 'flat',
					 'use_hidden_element' => true,
					 'checked_value' => 'absent',
					 'unchecked_value' => 'present'
				 ),
			 ));
			}
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