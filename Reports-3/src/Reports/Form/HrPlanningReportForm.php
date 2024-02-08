<?php

namespace Reports\Form;

use Zend\Form\Form;

class HrPlanningReportForm extends Form
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
                 'class'=>'control-label',
				 'empty_option' => 'Select a Report',
                 'value_options' => array(
                      'recruitment_position_level' => 'Recruitment By Position Level',
					  'recruitment_agencies' => 'Recruitment By Agencies',
					  'separation_agencies_position' => 'Separation By Agencies and Position Level',
					  'promotions' => 'Promotions Record',
					  'promotion_recruitment_separation' => 'Promotion, Recruitment and Separation Record'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
                $this->add(array(
			'name' => 'report_type',
			'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Select a Report Type',
                 'value_options' => array(
                                'list' => 'List/Details',
                                'summary' => 'Summary (Nos)'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
                
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
           'name' => 'five_year_plan',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Five Year Plan',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
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