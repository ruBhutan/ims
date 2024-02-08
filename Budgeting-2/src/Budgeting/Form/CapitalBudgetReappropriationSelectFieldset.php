<?php

namespace Budgeting\Form;

use Budgeting\Model\BudgetReappropriationSelect;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CapitalBudgetReappropriationSelectFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('budgetreappropriation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new BudgetReappropriationSelect());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));
		 
		 
		  $this->add(array(
           'name' => 'from_broad_head_name_id',
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
		 
		 $this->add(array(
           'name' => 'from_activity_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Activity Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_object_code_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Object Code',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_broad_head_name_id',
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
		 
		 $this->add(array(
           'name' => 'to_activity_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Activity Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_object_code_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Object Code',
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