<?php

namespace JobPortal\Form;

use JobPortal\Model\EmploymentDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class WorkExperienceFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('workexperience');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmploymentDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
			'name' => 'working_agency',
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
			'name' => 'occupational_group',
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
			'name' => 'position_category',
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
			'name' => 'position_title',
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
			'name' => 'position_level',
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
			'name' => 'start_period',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
                    'id' => 'single_cal3'
				),
		));
                
		$this->add(array(
			'name' => 'end_period',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
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
				'required' => false
			),
		));

		$this->add(array(
           'name' => 'employment_record_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'employment_record_file',
				  'required' => false,
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
     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
			 'employment_record_file' => array(
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