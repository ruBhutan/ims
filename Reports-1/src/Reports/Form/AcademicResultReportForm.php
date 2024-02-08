<?php

namespace Reports\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class AcademicResultReportForm extends Form
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
                    'academic_assessment_status' => 'Academic Assessment Status',
                    'academic_marks_report' => 'Academic Marks Report',
                    'academic_result_summary' => 'Academic Result Summary',
                    'student_list_for_academics' => 'Student List For Academics',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
             ),
         ));
		
		$this->add(array(
			'name' => 'organisation_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectStdRegisterOrganisation',
                  'required' => true,
             ),
         ));

         $this->add(array(
            'name' => 'programmes_id',
             'type'=> 'Select',
              'options' => array(
                 'empty_option' => 'Please Select Programme',
                  'class'=>'control-label',
                  'disable_inarray_validator' => true,
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'id' => 'selectselectStdRegisterProgramme',
                   'options' => array(),
                   'required' => true,
              ),
          ));

          $this->add(array(
            'name' => 'section',
             'type'=> 'Select',
              'options' => array(
                 'empty_option' => 'Please Select Programme',
                  'class'=>'control-label',
                  'disable_inarray_validator' => true,
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'required' => true,
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