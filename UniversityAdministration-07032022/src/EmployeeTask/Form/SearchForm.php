<?php

namespace EmployeeTask\Form;

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
			'name' => 'staff_name',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Staff First Name',
				),
		));
		
		$this->add(array(
			'name' => 'staff_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Staff ID',
				),
		));

		$this->add(array(
           'name' => 'from_date',
            'type'=> 'Text',

             'options' => array(
                 'class'=>'control-label',     
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                'class' => 'form-control',
                'placeholder'=>'yyyy-mm-dd',
                'id' => 'single_cal3'
             ),
         ));
         
         $this->add(array(
           'name' => 'to_date',
            'type'=> 'Text',

             'options' => array(
                 'class'=>'control-label',     
             ),
             'attributes' => array(
                'class' => 'form-control fa fa-calendar-o',
                'class' => 'form-control',
                'placeholder'=>'yyyy-mm-dd',
                'id' => 'single_cal4'
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