<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeeEducation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeEducationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeeeducation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeeEducation());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
			'name' => 'study_level',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Studey Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 
		 $this->add(array(
           'name' => 'field_study',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
				  'class' => 'form-control',
				  'placeholder' => 'Field of Study',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'study_mode',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Mode of Study',
				 'class' => 'control-label',
				 'value_options' => array(
				 		'Full Time on Campus' => 'Full Time on Campus',
						'Mixed Mode' => 'Mixed Mode',
						'Part-Time' => 'Part-Time'
				 )
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_name',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
            'attributes' => array(
				  'class' => 'form-control',
				  'placeholder' => 'College Name',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_location',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control',
				  'placeholder' => 'Address/City',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_country',
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
		 		 		 
		 $this->add(array(
           'name' => 'start_date',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                 'class' => 'form-control fa fa-calendar-o',
				'class' => 'form-control',
				'id' => 'single_cal3'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'end_date',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
				'class' => 'form-control',
				'id' => 'single_cal4'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'funding',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Funding Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'marks_obtained',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control',
				  'placeholder' => 'Aggregate Marks',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Text',
             'attributes' => array(
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
				'value' => 'Add Education',
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
            'target' => './data/emp_education_details',
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