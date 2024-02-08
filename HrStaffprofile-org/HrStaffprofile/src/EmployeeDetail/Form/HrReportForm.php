<?php

namespace EmployeeDetail\Form;

use Zend\Form\Form;

class HrReportForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
			'name' => 'report_type',
			'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select a Report',
                 'value_options' => array(
                      'position_category_position_level' => 'By Position Category and Position Level',
					  'agency_employment_type' => 'By Agency and Employment Type',
					  'agency_category_level' => 'By Agency, Position Category and Position Level',
					  'occupational_group_category' => 'By Occupational Group and Position Category',
					  'occupational_group_gender' => 'By Occupational Group and Gender',
					  'position_level_gender' => 'By Position Level and Gender'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'report_format',
			'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select a Report Format',
                 'value_options' => array(
                      'list' => 'Details/Listing',
					  'count' => 'Total Numbers/Count',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		
		
		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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