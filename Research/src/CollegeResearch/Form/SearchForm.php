<?php

namespace CollegeResearch\Form;

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
			'name' => 'researcher_name',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Researcher Name',
				),
		));
		
		$this->add(array(
			'name' => 'research_title',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Research Title',
				),
		));
				
		$this->add(array(
			'name' => 'grant_type',
			'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
				 'value_options' => array(
				 		'Beginner' => 'Begin Faculty Researcher',
						'Early-Career' => 'Early-Career Researcher',
                        'Mid-Career' => 'Mid-Career Researcher',
                        'Advanced-Career' => 'Advanced-Career Researcher',
				  ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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