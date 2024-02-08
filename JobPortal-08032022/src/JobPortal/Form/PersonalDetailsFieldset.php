<?php

namespace JobPortal\Form;

use JobPortal\Model\PersonalDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class PersonalDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('personaldetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new PersonalDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
			'name' => 'first_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter FirstName --',
			),
			
		));
		
		$this->add(array(
			'name' => 'middle_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter MiddleName-- ',
			),
		));
		
		$this->add(array(
			'name' => 'last_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter LastName--',
				),
		));
	   
		$this->add(array(
			'name' => 'cid',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Citizen Identity Card No',
			),
		));

		$this->add(array(
			'name' => 'email',
			'type' => 'email',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
			),
		));
		
		$this->add(array(
			'name' => 'nationality',
			'type' => 'Select',
			'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Gender',
                 'value_options' => array(
                    )
             ),
			'attributes' =>array(
				'class' => 'form-control',
			),
		));

		$this->add(array(
			'name' => 'date_of_birth',
			'type' => 'Date',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Date of Birth',
			),
		));
		
		$this->add(array(
			'name' => 'house_no',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'House No.',
			),
		));
		
		$this->add(array(
			'name' => 'thram_no',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Thram No.',
			),
		));
		
		$this->add(array(
			'name' => 'gender',
			'type' => 'Select',
			'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Gender',
                 'value_options' => array(
                    )
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Gender',
				),
		));
		
		$this->add(array(
			'name' => 'maritial_status',
			'type' => 'Select',
			'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Maritial Status',
                 'value_options' => array(
                    )
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Marital Status',
				),
		));

		$this->add(array(
			'name' => 'contact_no',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
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
           'name' => 'cid_copy',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'cid_copy',
				  'required' => false,
             ),
         ));


		$this->add(array(
           'name' => 'profile_picture',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'profile_picture',
				  'required' => false,
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
			 'cid_copy' => array(
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

			 'profile_picture' => array(
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
		        			'extension' => ['png','jpg','jpeg'],
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