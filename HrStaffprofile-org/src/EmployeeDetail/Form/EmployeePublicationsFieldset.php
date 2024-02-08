<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeePublications;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeePublicationsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeepublication');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeePublications());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
			'name' => 'publication_year',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		 $this->add(array(
			'name' => 'publication_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
          
         $this->add(array(
			'name' => 'research_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Research Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'publisher',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'publication_url',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'publication_no',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'author_level',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
        /*     
		$this->add(array(
			'name' => 'submission_date',
			'type' => 'date',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
			),
			
		));
		
		$this->add(array(
			'name' => 'publication_file',
			'type' => 'file',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
			),
		));
		
		$this->add(array(
			'name' => 'remarks',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		*/
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
           'name' => 'evidence_file',
            'type'=> 'file',
            'options' => array(
             'class' => 'control-label',
            ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
            
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'ADD Publication',
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
            'target' => './data/emp_previous_research',
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