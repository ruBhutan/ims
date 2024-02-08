<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeeResponsibilities;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeResponsibilityFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeeresponsibility');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeeResponsibilities());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'responsibility_category_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select Responsibility Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'employee_details_id',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
                
		$this->add(array(
			'name' => 'responsibility_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'start_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control fa fa-calendar-o',
				'class' => 'form-control',
				'id' => 'single_cal3'
				),
		));
		
		$this->add(array(
			'name' => 'end_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control fa fa-calendar-o',
				'class' => 'form-control',
				'id' => 'single_cal4'
				),
		));
		
		$this->add(array(
			'name' => 'remarks',
			'type' => 'TextArea',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'rows' => 5,
				),
		));

		$this->add(array(
           'name' => 'evidence_file',
            'type'=> 'file',
            'options' => array(
             'class' => 'control-label',
            ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
            
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Add Responsibility',
				'id' => 'submitbutton',
				'class' => 'btn btn-success',
				),
			));
		
		$this->add(array(
			'name' => 'approve',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Approve',
				'id' => 'approve',
				'class' => 'btn btn-success',
				),
			));
		
		$this->add(array(
			'name' => 'reject',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Reject',
				'id' => 'reject',
				'class' => 'btn btn-danger',
				),
			));


     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
           return array(
       'evidence_file' => array(
        'required' => false,
        'validators' => array(
          array(
          	'name' => 'FileUploadFile',
          ),
          array(
                'name' => 'Zend\Validator\File\Size',
                'options' => array(
                    'min' => '10kB',
                    'max' => '2MB',
                ),
            ),
            array(
                'name' => 'Zend\Validator\File\Extension',
                'options' => array(
                    'extension' => ['png','jpg','jpeg','pdf'],
                ),
            ),
        ),
        'filters' => array(
          array(
          'name' => 'FileRenameUpload',
          'options' => array(
            'target' => './data/emp_responsibilities',
            'useUploadName' => true,
            'useUploadExtension' => true,
            'overwrite' => true,
            'randomize' => true
            ),
          ),
        ),
       ),
         );
     }
}