<?php

namespace JobPortal\Form;

use JobPortal\Model\TrainingDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class TrainingsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('trainings');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new TrainingDetails());
         
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
				'required' => true,
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
				'required' => true,
				),
		));
                
		$this->add(array(
			'name' => 'institute_address',
			'type' => 'TextArea',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'required' => true,
			),
			
		));
		
		$this->add(array(
           'name' => 'country',
            'type'=> 'Select',
			'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Training Country',
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
           'name' => 'from_date',
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
			'name' => 'to_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
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
                 'empty_option' => 'Select Funding Category',
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
           'name' => 'training_certificate',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'training_certificate',
				  'required' => true,
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
			 'training_certificate' => array(
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