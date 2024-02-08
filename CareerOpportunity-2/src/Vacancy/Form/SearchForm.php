<?php

namespace Vacancy\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class SearchForm extends Form implements InputFilterProviderInterface
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
			'type' => 'select',
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
			 'type'=> 'Select',
			  'options' => array(
				  'empty_option' => 'Please Select Position Title',
				  'disable_inarray_validator' => true,
				  'class'=>'control-label',
	 
			  ),
			  'attributes' => array(
				   'class' => 'form-control ',
				   'required' => false,
			  ),
		  ));      
		
		$this->add(array(
			'name' => 'position_category',
			'type' => 'select',
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

	/**
      * @return array
      */
	  public function getInputFilterSpecification()
	  {
		 return [
			 [
				 'name'       => 'working_agency',
				 'required'   => false,
			 ],
			 [
				 'name'       => 'position_title',
				 'required'   => false,
			 ],
			 [
				 'name'       => 'position_category',
				 'required'   => false,
			 ],
		 ];
	  }

}