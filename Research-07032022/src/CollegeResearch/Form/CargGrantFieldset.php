<?php

namespace CollegeResearch\Form;

use CollegeResearch\Model\CargGrant;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CargGrantFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('carggrant');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CargGrant());
         
         $this->setAttributes(array(
                    'class' => 'form-group form-horizontal form-label-left',
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
				 		             'Beginner' => 'Begin Faculty Researcher',
						            'Early-Career' => 'Early-Career Researcher',
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
                  'required' => true
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
           'name' => 'carg_category_type',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'empty_option' => 'Co-Researcher Type',
                  'value_options' => array(
                    'ECR –Early Career Researcher' => 'ECR –Early Career Researcher',
                    'MCR – Middle career researcher' => 'MCR – Middle career researcher',
                    'ACR – Advanced career Researcher' => 'ACR – Advanced career Researcher'
          ),
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
		 	   'name' => 'coresearchers',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=> 1,
					'should_create_template' => true,
					'allow_add' => true,
                    'allow_remove' => true,
					'target_element' => array(
						'type' => 'CollegeResearch\Form\CoResearchersFieldset',
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
             'name' => array(
                 'required' => false,
             ),
         );
     }
}