<?php

namespace BudgetTransactions\Form;

use BudgetTransactions\Model\BudgetTransactionsSelect;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BudgetTransactionsSelectFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('budgettransactions');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new BudgetTransactionsSelect());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

		 /*
		 $this->add(array(
           'name' => 'chart_of_accounts_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Chart of Accounts',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));*/
		 
		  $this->add(array(
           'name' => 'from_accounts_group_head_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Accounts Group Head',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_budget_ledger_head_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Ledger Head',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_accounts_group_head_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Accounts Group Head',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_budget_ledger_head_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Ledger Head',
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