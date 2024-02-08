<?php

namespace Budgeting\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class BudgetProposalForm extends Form
{
	public function __construct($name = null, array $options = [])
     {
        /*parent::__construct('budgetproposal');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 */
		 parent::__construct('ajax', $options);
		
		$this->adapter1 = $name; 
		$this->ajax = $name; 
        $this->ajax = $options;
		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'type' => 'Budgeting\Form\BudgetProposalFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'accounts_group_head_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Accounts Group Head',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectAccountsGroupHead',
				  'options' => $this->createAccountsGroupHead(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'chart_of_accounts_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Chart of Accounts',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectChartAccounts',
				  'options' => array(),
             ),
         ));
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));

         $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                 'type' => 'submit',
                 'value' => 'Send',
             ),
         ));
     }
	 
	private function createAccountsGroupHead()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT id, group_head FROM accounts_group_head';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['group_head'];
        }
        return $selectData;
    }
    
}