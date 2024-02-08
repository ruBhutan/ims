<?php

namespace JobPortal\Form;

use JobPortal\Model\EducationDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EducationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('education');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EducationDetails());
         
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
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Study Level',
				 'value_options' => array(
				 		
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
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
				  'placeholder' => 'Field of Study (General/Arts/Commerce/Science for class below XII and actual field above XII)',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'study_mode',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select Mode of Study',
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
				  'placeholder' => 'School/Institute/College Name',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_location',
            'type'=> 'Textarea',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control',
				  'placeholder' => 'Address/City',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_country',
            'type'=> 'Select',
			'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Country',
				 'value_options' => array(
				 		
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'placeholder' => 'Country',
				  'required' => true
             ),
         ));
		 		 		 
		 $this->add(array(
           'name' => 'start_date',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
                    'id' => 'single_cal3',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'end_date',
            'type'=> 'Text',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
                    'id' => 'single_cal4',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'funding',
            'type'=> 'Select',
			'options' => array(
				 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Funding',
				 'value_options' => array(
				 		
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'placeholder' => 'Funding',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'marks_obtained',
            'type'=> 'number',
			'options' => array(
				'class' => 'control-label',
			),
             'attributes' => array(
                  'class' => 'form-control',
				  'placeholder' => 'Aggregate Marks',
				  'min' => 0.0,
  				  'step' => 0.01,
				  'required' => true
             ),
         ));

         $this->add(array(
           'name' => 'academic_transcript',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'academic_transcript',
				  'required' => true,
             ),
         ));

         $this->add(array(
           'name' => 'pass_certificate',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'pass_certificate',
				  'required' => true,
             ),
         ));
		
		$this->add(array(
			'name' => 'job_applicant_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
			),
		));

		$this->add(array(
			'name' => 'last_updated',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
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
			 'academic_transcript' => array(
			 	'required' => false,
				'validators' => array(
					array(
					'name' => 'FileUploadFile',
					),
				),
				'filters' => array(
					array(
					'name' => 'FileRenameUpload',
					'options' => array(
						'target' => './data/job_applicant',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),

			 'pass_certificate' => array(
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
						'target' => './data/job_applicant',
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