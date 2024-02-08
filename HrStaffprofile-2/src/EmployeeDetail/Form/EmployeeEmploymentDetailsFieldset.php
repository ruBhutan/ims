<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeePersonalDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeEmploymentDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employmentdetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeePersonalDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
              
                
        $this->add(array(
			'name' => 'recruitment_date',
			'type' => 'Text',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
                    'id' => 'single_cal3',
                ),
            ));
                
        $this->add(array(
			'name' => 'organisation_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
		));
        
        $this->add(array(
			'name' => 'departments_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Department',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
		));
                
        $this->add(array(
			'name' => 'departments_units_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Department Unit',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
		));

         $this->add(array(
			'name' => 'emp_type',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Employment Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
		));


         $this->add(array(
			'name' => 'position_level_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
		));


         $this->add(array(
			'name' => 'position_title_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
		));
                
            
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success',
				),
			));
     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'name' => array(
                 'required' => false,
             ),
         );
     }
}