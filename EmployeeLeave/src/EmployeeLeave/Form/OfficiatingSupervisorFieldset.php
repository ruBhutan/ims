<?php

namespace EmployeeLeave\Form;

use EmployeeLeave\Model\OfficiatingSupervisor;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OfficiatingSupervisorFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('officiatingsupervisor');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new OfficiatingSupervisor());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
		 		'name' => 'id',
				'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'supervisor',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please select Supervisor',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
         
         $this->add(array(
			'name' => 'date_range',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' => array(
                  'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
				  'id' => 'reservation',
				  'required' => true
             ),  
         ));
         
         
          $this->add(array(
			'name' => 'officiating_supervisor',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Assign Supervisor',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
          
         $this->add(array(
			'name' => 'remarks',
			'type'=>'textarea',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' => array(
				'class' => 'form-control',
				'rows'=>'3',
			),
         ));

         $this->add(array(
             'name' => 'evidence_file',
             'type' => 'file',
             'options' => array(
                 'class' => 'form-control',
                 'value' => 'Choose File',
              ),   
             'attributes' => array(
                  'class' => 'form-control',
                    'id' => 'evidence_file',
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
				'name' => 'approve',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Approve',
					'id' => 'Approve',
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
             'evidence_file' => array(
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
                        'target' => './data/emp_officiating_file',
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