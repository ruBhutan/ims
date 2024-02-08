<?php

namespace Reports\Form;

use Zend\Form\Form;

class HrTrainingReportForm extends Form
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
                      'five_year_implementation' => 'Progress of FYP Implementation',
					  'training_implementation' => 'Training Implementation',
					  'training_implementation_category' => 'Training Implementation by Category',
					  'training_implementation_country' => 'Training Implementation by Country',
					  'training_implementation_funding' => 'Training Implementation by Source of Funding'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
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