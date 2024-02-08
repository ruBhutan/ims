<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\AurgActionPlan;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AurgActionPlanFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurgactionplan');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AurgActionPlan());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
		 $this->add(array(
           'name' => 'crc_approval_no',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => false,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'ethical_committee_approval_no',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));

		 $this->add(array(
           'name' => 'application_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'amount_approved',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'application_step_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
		 	   'name' => 'actionplan',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => '__placeholder__',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'UniversityResearch\Form\AurgActionPlanBudgetFieldset',
					),
			   ),
		 ));
		 
     $this->add(array(
             'name' => 'related_documents',
             'type' => 'file',
             'options' => array(
                 'class' => 'form-control',
                 'value' => 'Choose File',
              ),   
             'attributes' => array(
                  'class' => 'form-control',
                    'id' => 'related_documents',
                    'required' => false,
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
             'name' => array(
                 'required' => false,
             ),
             'related_documents' => array(
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
                        'target' => './data/research',
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