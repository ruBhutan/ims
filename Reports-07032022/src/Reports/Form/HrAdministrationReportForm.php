<?php

namespace Reports\Form;

use Zend\Form\Form;

class HrAdministrationReportForm extends Form
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
                
                $this->add(array(
			'name' => 'date',
			'type'=> 'text',
                        'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                             'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
                                             'id' => 'reservation'
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