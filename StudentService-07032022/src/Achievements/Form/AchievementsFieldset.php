<?php

namespace Achievements\Form;

use Achievements\Model\Achievements;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AchievementsFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('achievements');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Achievements());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'organisation_id',
              'type' => 'Hidden'  
         ));
          

         $this->add(array(
            'name' => 'achievement_name',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Achievements Category',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));
		 
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'evidence_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                 'class' => 'form-control ',
                 'id' => 'evidence_file',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'student_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
             'evidence_file' => array(
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
                                    'target' => './data/student',
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