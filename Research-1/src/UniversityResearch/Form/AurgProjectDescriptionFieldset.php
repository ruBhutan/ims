<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\AurgProjectDescription;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AurgProjectDescriptionFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurgprojectdescription');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AurgProjectDescription());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));
           
		 $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
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