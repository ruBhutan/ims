<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\AurgTitle;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AurgTitleFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurgtitle');
		
		$this->setHydrator(new ClassMethodsHydrator(false));
		$this->setObject(new AurgTitle());
         
         $this->setAttributes(array(
                'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'grant_type',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select a Grant',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
         $this->add(array(
           'name' => 'grant_applied_for',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
				 'value_options' => array(
				 		'Beginner' => 'Beginner Researcher',
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
		 	   'name' => 'aurgresearchers',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => '__placeholder__',
					'allow_add' => true,
					'allow_remove' => true,
					'target_element' => array(
						'type' => 'UniversityResearch\Form\AurgResearchersFieldset',
					),
			   ),
		 ));
         //End of project title and co-researcher fields


         //Start of project description fields
         $this->add(array(
           'name' => 'problem_statement',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));
         
          $this->add(array(
           'name' => 'research_questions',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));
         
          $this->add(array(
           'name' => 'review_key_literature',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));

         $this->add(array(
           'name' => 'approach_paradigm_theory',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         )); 
         
         $this->add(array(
           'name' => 'data_collection_procedures',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));

         $this->add(array(
           'name' => 'data_analysis_procedures',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));

         $this->add(array(
           'name' => 'data_presentation',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));

         $this->add(array(
           'name' => 'ethical_considerations',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         )); 
         
         $this->add(array(
           'name' => 'significance_of_study',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));

         $this->add(array(
           'name' => 'research_dissemination',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));

         $this->add(array(
           'name' => 'references',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control',
                  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));
         //End of project description fields


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

         
     //End of action plan fields		 
		  $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Save and Move to Next Form',
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
             'coresearcher_name' => array(
                 'required' => false,
             ),
         );
     }
}