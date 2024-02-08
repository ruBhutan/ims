<?php

namespace Budgeting\Form;

use Budgeting\Model\BudgetProposal;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BudgetRemarksFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('budgetremarks');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new BudgetProposal());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
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