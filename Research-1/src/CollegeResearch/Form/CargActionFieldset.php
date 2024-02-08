<?php
/*
 * Action Fieldset. Will take in BudgetPlanFieldset.
 * ActionPlanFieldset is dynamically added rows
*/

namespace CollegeResearch\Form;

use CollegeResearch\Model\CargActionPlan;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CargActionFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('cargactionplan');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CargActionPlan());
         
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
           'name' => 'crc_amount_granted',
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
           'name' => 'signed_certification_researchers',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'joining_report',
				  'required' => false,
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'signed_certification_crc',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'joining_report',
				  'required' => false,
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'research_proposal',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'professional_development_report',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
		 	   'name' => 'budgetplan',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'allow_add' => true,
					'target_element' => array(
						'type' => 'CollegeResearch\Form\CargBudgetPlanFieldset',
					),
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
			 'signed_certification_researchers' => array(
			 	'required' => false,
				'validators' => array(
					array(
					'name' => 'FileUploadFile',
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
			 'signed_certification_crc' => array(
			 	'required' => false,
				'validators' => array(
					array(
					'name' => 'FileUploadFile',
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
			 'research_proposal' => array(
			 	'required' => true,
				'validators' => array(
					array(
					'name' => 'FileUploadFile',
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