<?php

namespace Vacancy\Form;

use Vacancy\Model\JobApplication;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class JobApplicationFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('jobapplication');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new JobApplication());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'vacancy_announcements_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'employee_details_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'job_applicant_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'status',
              'type' => 'Hidden'  
         ));

         //Marsk fieldset
		 $this->add(array(
			'name' => 'x_english',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter Class X English Marks --',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
			
		));
		
		$this->add(array(
			'name' => 'x_sub1_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 10 Best one mark-- ',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));
		
		$this->add(array(
			'name' => 'x_sub2_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 10 Best two mark-- ',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
				),
		));
	   
		$this->add(array(
			'name' => 'x_sub3_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 10 Best three mark-- ',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));

		$this->add(array(
			'name' => 'x_sub4_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '----Class 10 Best three mark--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => false,
			),
		));
		
		$this->add(array(
			'name' => 'xll_english',
			'type' => 'number',
			'options' => array(
                 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 English Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));

		$this->add(array(
			'name' => 'xll_sub1_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 Best One Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));
		
		$this->add(array(
			'name' => 'xll_sub2_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 Best Two Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));
		
		$this->add(array(
			'name' => 'xll_sub3_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 Best Three Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));

         
            
            //references fieldset
                 $this->add(array(
			'name' => 'reference_name_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_title_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_position_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_organisation_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_relation_applicant_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_telephone_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_email_1',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
                
                $this->add(array(
			'name' => 'reference_name_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_title_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_position_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_organisation_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_relation_applicant_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_telephone_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
		
		$this->add(array(
			'name' => 'reference_email_2',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                            'required' => true
				),
		));
            //end of references fieldset
		 
		 $this->add(array(
           'name' => 'agreement',
            'type'=> 'checkbox',
             'options' => array(
                'class'=>'control-label',
				'use_hidden_element' => true,
				'checked_value' => 'no',
				),
			'attributes' => array(
				'class' => 'flat',
				'value' => 'yes',
				'required' => true
			)
		));
		 
		 $this->add(array(
           'name' => 'identity_proof',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 		 
         $this->add(array(
           'name' => 'security_clearance_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'medical_clearance_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));

         $this->add(array(
           'name' => 'audit_clearance_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'tax_clearance_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => false
             ),
         ));
		 
		 $this->add(array(
           'name' => 'other_certificate_description',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'security_clearance_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'security_clearance_file',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'medical_clearance_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'medical_clearance_file',
				  'required' => true
             ),
         ));

         $this->add(array(
           'name' => 'audit_clearance_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'medical_clearance_file',
				  'required' => false
             ),
         ));
		 
		 $this->add(array(
           'name' => 'tax_clearance_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'tax_clearance_file',
				  'required' => false
             ),
         ));
		 
		 $this->add(array(
           'name' => 'other_certificate_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'other_certificate_file',
				  'required' => true
             ),
         ));

         $this->add(array(
			'name' => 'application_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
                'required' => false
				),
		));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Apply',
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
             'agreement' => array(
                 'required' => true,
             ),
			 'identity_proof' => array(
			 	'required' => true,
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
						'target' => './data/jobapplication',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'security_clearance_file' => array(
			 	'required' => true,
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
						'target' => './data/jobapplication',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'medical_clearance_file' => array(
			 	'required' => true,
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
						'target' => './data/jobapplication',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'audit_clearance_file' => array(
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
						'target' => './data/jobapplication',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'tax_clearance_file' => array(
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
						'target' => './data/jobapplication',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'other_certificate_file' => array(
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
						'target' => './data/jobapplication',
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