<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeeProfilePicture;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeProfilePictureFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('profilepicture');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeeProfilePicture());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
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
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Add Profile Picture',
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
			 'profile_picture' => array(
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
						'target' => './data/profilepicture',
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