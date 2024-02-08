<?php

namespace EmpTraining\Form;

use EmpTraining\Model\LongTermApplication;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Session\Container;

class UpdateLongTermApplicationFieldset extends Fieldset implements InputFilterProviderInterface
{
	
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('longtermtraining');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new LongTermApplication());

         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'employee_details_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'training_details_id',
              'type' => 'Hidden'  
         ));
		 		 
		 $this->add(array(
           'name' => 'audit_clearance',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'audit_clearance',
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'security_clearance',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'security_clearance',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'medical_certificate',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'medical_certificate',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'course_content_schedule',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'course_content_schedule',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'acceptance_letter',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'acceptance_letter',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'professional_development_report',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'professional_development_report',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'search',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search',
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
			 'audit_clearance' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'security_clearance' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'medical_certificate' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'course_content_schedule' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'professional_development_report' => array(
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
						'target' => './data/training',
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