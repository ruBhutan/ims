<?php

namespace Hostel\Form;

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
           'name' => 'hostels_list_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Hostel',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control ',
				  'required' => true,
             ),
         ));
		
		$this->add(array(
			'name' => 'room_no',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Room No',
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
