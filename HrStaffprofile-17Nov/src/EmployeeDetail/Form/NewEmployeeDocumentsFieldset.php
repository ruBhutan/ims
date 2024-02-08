<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\NewEmployeeDocuments;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewEmployeeDocumentsFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeedocuments');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new NewEmployeeDocuments());
         
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
           'name' => 'passport_photo',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'identity_proof',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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
			 'passport_photo' => array(
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
		                    'extension' => ['png','jpg','jpeg'],
		                ),
		            ),
				),
				'filters' => array(
					array(
					'name' => 'FileRenameUpload',
					'options' => array(
						'target' => './data/staff_documents',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
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
						'target' => './data/staff_documents',
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
						'target' => './data/staff_documents',
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
						'target' => './data/staff_documents',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'other_certificate_file' => array(
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
						'target' => './data/staff_documents',
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