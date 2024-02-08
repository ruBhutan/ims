<?php

namespace StudentReports\Form;

use Zend\Form\Form;

class StudentReportForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
			'name' => 'report_name',
			'type'=> 'select',
      'options' => array(
        'empty_option' => 'Select a Report',
        'disable_inarray_validator' => true,
        'class'=>'control-label',
      ),
      'attributes' => array(
           'class' => 'form-control ',
      ),
    ));
                
      /*    $this->add(array(
			'name' => 'report_type',
			'type'=> 'select',
          'options' => array(
              'class'=>'control-label',
                              'empty_option' => 'Select a Report Type',
              'value_options' => array(
                             'list' => 'List/Details',
                             'summary' => 'Summary/Consolidated'
              ),
          ),
          'attributes' => array(
               'class' => 'form-control',
          ),
      ));*/
                
    $this->add(array(
      'name' => 'organisation',
       'type'=> 'select',
        'options' => array(
          'empty_option' => 'Select an Agency/College',
          'disable_inarray_validator' => true,
          'class'=>'control-label',
        ),
        'attributes' => array(
          'class' => 'form-control ',
        ),
    ));
    
    $this->add(array(
      'name' => 'year',
       'type'=> 'select',
        'options' => array(
          'empty_option' => 'Select a Year',
          'disable_inarray_validator' => true,
          'class'=>'control-label',
        ),
        'attributes' => array(
          'class' => 'form-control ',
          'required' => true
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