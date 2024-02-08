<?php

namespace Reports\Form;

use Zend\Form\Form;

class StudentFeedbackReportForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
			'name' => 'organisation_id',
			'type'=> 'hidden'
                    ));
                
                $this->add(array(
			'name' => 'report_type',
			'type'=> 'select',
                        'options' => array(
                            'class'=>'control-label',
                                            'empty_option' => 'Please Select a Report',
                            'value_options' => array(
                                   'overall_feedback_ratings' => 'Over All Feedback Ratings',
                                   'individual_feedback_ratings' => 'Individual Staff Ratings',
                                  // 'total_student_report' => 'Total Student Report',
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