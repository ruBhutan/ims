<?php

namespace EmpTraining\Form;

use EmpTraining\Model\HrLongTermApplication;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Session\Container;

class HrLongTermApplicationFieldset extends Fieldset implements InputFilterProviderInterface
{
	
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('longtermtraining');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new HrLongTermApplication());

         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'award_letter',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'award_letter',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'understanding_letter',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'understanding_letter',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'departure_intimidation_form',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'departure_intimidation_form',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'predeparture_briefing_form',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'predeparture_briefing_form',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'understanding_secondment',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'understanding_secondment',
				  'required' => true,
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
     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
			 'award_letter' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),

			 'understanding_letter' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'departure_intimidation_form' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'predeparture_briefing_form' => array(
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
						'target' => './data/training',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
			 'understanding_secondment' => array(
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