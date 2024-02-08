<?php

namespace BudgetTransactions\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class BudgetTransactionsForm extends Form
{
	protected $toCount = array();
	protected $fromCount = array();
	
	public function __construct($toCount, $fromCount)
     {
        parent::__construct('budgettransactions');
		
		$this->toCount = $toCount;
		$this->fromCount = $fromCount;
         
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
        
		foreach($this->toCount as $toKey => $toValue)
		{ 
			$this->add(array(
			   'name' => 'to_'.$toValue,
				'type'=> 'text',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
				 ),
			 ));
		}
		
		foreach($this->fromCount as $fromKey => $fromValue)
		{ 
			$this->add(array(
			   'name' => 'from_'.$fromValue,
				'type'=> 'text',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
				 ),
			 ));
		}
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
             )
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