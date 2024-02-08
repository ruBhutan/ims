<?php

namespace Budgeting\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CapitalBudgetReappropriationForm extends Form
{
	
	public function __construct()
     {
        parent::__construct('budgetreappropriation');
		
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
		
		$this->add(array(
			   'name' => 'reference_no',
				'type'=> 'text',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
				 ),
			 ));
			 
		$this->add(array(
			   'name' => 'reference_date',
				'type'=> 'date',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
				 ),
			 ));
			 
		$this->add(array(
			   'name' => 'purpose',
				'type'=> 'textarea',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
					  'rows' => 3
				 ),
			 ));
        
		$this->add(array(
		   'name' => 'to_proposal_id',
			'type'=> 'text',
			 'options' => array(
				 'class'=>'control-label',
			 ),
			 'attributes' => array(
				  'class' => 'form-control',
			 ),
		 ));
		
		$this->add(array(
		   'name' => 'from_proposal_id',
			'type'=> 'text',
			 'options' => array(
				 'class'=>'control-label',
			 ),
			 'attributes' => array(
				  'class' => 'form-control',
			 ),
		 ));
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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
}