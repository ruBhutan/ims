<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\AurgGrant;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AurgGrantFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurggrant');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AurgGrant());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'grant_applied_for',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
				 'value_options' => array(
				 		'Beginner' => 'Begin Researcher',
                        'Mid-Career' => 'Mid-Career Researcher',
                        'Advanced-Career' => 'Advanced-Career Researcher',
				  ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'research_title',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'placeholder' =>'Enter Research/Project Title',
             ),
         ));
           
         $this->add(array(
           'name' => 'research_year',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
		 $this->add(array(
           'name' => 'problem_statement',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'research_questions',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'review_key_literature',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'approach_paradigm_theory',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         )); 
		 
		 $this->add(array(
           'name' => 'data_collection_procedures',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'data_analysis_procedures',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'data_presentation',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'ethical_considerations',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         )); 
		 
		 $this->add(array(
           'name' => 'significance_of_study',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'research_dissemination',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'references',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
		 	   'name' => 'aurgactionplanbudget',
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
		 	   'name' => 'aurgresearchers',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => '__placeholder__',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'UniversityResearch\Form\AurgResearchersFieldset',
					),
			   ),
		 ));
		 
		 $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));
		 
		 $this->add(array(
			'name' => 'next',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Next',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
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
         );
     }
}