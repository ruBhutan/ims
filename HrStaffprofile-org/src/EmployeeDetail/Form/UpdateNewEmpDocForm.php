<?php
//used when adding new employees
namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UpdateNewEmpDocForm extends Form implements InputFilterProviderInterface
{

    public function __construct()
     {

         parent::__construct();
         		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

          $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 

		$this->add(array(
			'name' => 'announcement_doc',
			'type' => 'file',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

		$this->add(array(
			'name' => 'shortlist_doc',
			'type' => 'file',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

		$this->add(array(
			'name' => 'selection_doc',
			'type' => 'file',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

		$this->add(array(
			'name' => 'minutes_doc',
			'type' => 'file',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

		$this->add(array(
			'name' => 'emp_application_form_doc',
			'type' => 'file',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
			));

			$this->add(array(
				'name' => 'emp_academic_transcript_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));

			$this->add(array(
				'name' => 'emp_training_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));

			$this->add(array(
				'name' => 'emp_cid_wp_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));

			$this->add(array(
				'name' => 'emp_security_cl_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));

			$this->add(array(
				'name' => 'emp_medical_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));


			$this->add(array(
				'name' => 'emp_no_objec_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));

				$this->add(array(
					'name' => 'appointment_order_doc',
					'type' => 'file',
					'options' => array(
							'class'=>'control-label',
						),
						'attributes' => array(
							'class' => 'form-control',
						),
					));

			$this->add(array(
				'name' => 'others_doc',
				'type' => 'file',
				'options' => array(
						'class'=>'control-label',
					),
					'attributes' => array(
						'class' => 'form-control',
					),
				));
			


		$this->add(array(
			'name' => 'new_employee_details_id',
			'type' => 'text',
			'options' => array(
                    'class'=>'control-label',
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
					'timeout' => 1200
             	)
	     )
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
			 'announcement_doc' => array(
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
						'target' => './data/new_employee_docs',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'shortlist_doc' => array(
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
						'target' => './data/new_employee_docs',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'selection_doc' => array(
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
						'target' => './data/new_employee_docs',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
            'minutes_doc' => array(
			 	'required' => false,
                                'allow_empty' => true,
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
						'target' => './data/new_employee_docs',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),

            'emp_application_form_doc' => array(
			 	'required' => false,
                                'allow_empty' => true,
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
						'target' => './data/new_employee_docs',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),

			 'emp_academic_transcript_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'emp_training_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'emp_cid_wp_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'emp_security_cl_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'emp_medical_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'emp_no_objec_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'appointment_order_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
					   'useUploadName' => true,
					   'useUploadExtension' => true,
					   'overwrite' => true,
					   'randomize' => true
					   ),
				   ),
			   ),
			),

			'others_doc' => array(
				'required' => false,
							   'allow_empty' => true,
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
					   'target' => './data/new_employee_docs',
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
