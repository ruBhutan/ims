<?php

namespace Vacancy\Form;

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
			'name' => 'working_agency',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Agency Name',
				),
		));
		
		$this->add(array(
			'name' => 'position_title',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Position Title',
				),
		));
		
		$this->add(array(
			'name' => 'position_category',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Position Category',
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