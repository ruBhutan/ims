<?php

namespace Budgeting\Form;

use Budgeting\Model\CapitalBudgetProposal;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CapitalBudgetProposalFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('capitalbudgetproposal');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CapitalBudgetProposal());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'five_year_plan',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'disabled' => 'disabled',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'financial_year',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'disabled' => 'disabled',
             ),
         ));
           
         $this->add(array(
           'name' => 'budget_type',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'value_options' => array(
				 	'capital' => 'Capital Budget',
					'current' => 'Current Budget',
             	),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'proposed_budget_amount',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'write_up',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => '3',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'budget_amount_approved',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'budget_proposal_status',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 //the following fieldsets use ajax
		 /*
		 $this->add(array(
           'name' => 'object_code_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Object Code',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'broad_head_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Broad Head Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 */
		 $this->add(array(
           'name' => 'activity_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Activity',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'activity_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'departments_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Department',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'disabled' => 'disabled',
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