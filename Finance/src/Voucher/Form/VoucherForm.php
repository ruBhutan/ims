<?php

namespace Voucher\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class VoucherForm extends Form
{
	public function __construct()
	{
		  // we want to ignore the name passed
        parent::__construct('voucher');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
		
		$this->add(array(
             'type' => 'Voucher\Form\VoucherFieldset',
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
				  //'options' => $this->createAccountsGroupHead(),
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
				 // 'options' => array(),
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
				'class' => 'btn btn-success'
				),
		));
				
		$this->add(array(
			'name' => 'reset',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Reset',
				'id' => 'resetbutton',
				'class' => 'btn btn-default'
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