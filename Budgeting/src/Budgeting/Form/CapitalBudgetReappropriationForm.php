<?php

namespace Budgeting\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CapitalBudgetReappropriationForm extends Form
{
	protected $id;
	
	public function __construct($id)
     {
		parent::__construct('budgetreappropriation');
		
		$this->id = $id;
		
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
			'name' => 'id',
			'attributes' => array(
			'type' => 'Hidden',
			 ),   
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
				'type'=> 'Text',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					'class' => 'form-control fa fa-calendar-o',
					'required' => true,
					'id' => 'single_cal3'
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
		   'name' => 'from_amount'.$this->id,
			'type'=> 'text',
			 'options' => array(
				 'class'=>'control-label',
			 ),
			 'attributes' => array(
				  'class' => 'form-control',
			 ),
		 ));
		
		$this->add(array(
		   'name' => 'to_amount'.$this->id,
			'type'=> 'text',
			 'options' => array(
				 'class'=>'control-label',
			 ),
			 'attributes' => array(
				  'class' => 'form-control',
			 ),
		 ));

		 $this->add(array(
			'name' => 'status',
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