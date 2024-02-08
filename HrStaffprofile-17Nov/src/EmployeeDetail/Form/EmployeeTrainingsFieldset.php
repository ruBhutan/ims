<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeeTrainings;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeTrainingsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeetrainings');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeeTrainings());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
			'name' => 'course_title',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
          
         $this->add(array(
			'name' => 'institute_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
                
		$this->add(array(
			'name' => 'institute_address',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
			),
			
		));
		
		$this->add(array(
			'name' => 'country',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Country',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		/*
		
		$this->add(array(
			'name' => 'field_study',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
	   */
		$this->add(array(
			'name' => 'from_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control fa fa-calender-o',
				'id' => 'single_cal3',
			),
		));
		
		$this->add(array(
			'name' => 'to_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control fa fa-calender-o',
				'id' => 'single_cal4',
			),
		));

		$this->add(array(
			'name' => 'funding',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Funding Source',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		$this->add(array(
			'name' => 'employee_details_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
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
				'value' => 'Add Trainings',
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
            'target' => './data/emp_previous_research',
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