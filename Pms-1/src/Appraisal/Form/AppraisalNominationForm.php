<?php

namespace Appraisal\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AppraisalNominationForm extends Form
{
	protected $count;
	protected $peerCount;
	protected $beneficiaryCount;
	protected $subordinateCount;
	
	public function __construct($count)
     {
        parent::__construct('appraisals');
        
		$this->count = $count;
		
		$this->beneficiaryCount = $this->count['beneficiary'];
		$this->peerCount = $this->count['peer'];
		$this->subordinateCount = $this->count['subordinate'];
						
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        for($i=1; $i <= $this->beneficiaryCount; $i++)
		{
			$this->add(array(
			   'name' => 'beneficiary'.$i,
				'type'=> 'select',
					 'options' => array(
						 'empty_option' => 'Approve/Reject',
						 'class'=>'control-label',
					 ),
					 'attributes' => array(
						  'class' => 'form-control ',
						  'options' => array(
						  	   'Approved' => 'Approved',
							   'Rejected' => 'Rejected'
						  )
					 ),
				 ));			 
		}
		
		for($i==1; $i <= $this->peerCount; $i++)
		{
			$this->add(array(
			   'name' => 'peer'.$i,
				'type'=> 'select',
					 'options' => array(
						 'empty_option' => 'Approve/Reject',
						 'class'=>'control-label',
					 ),
					 'attributes' => array(
						  'class' => 'form-control ',
						  'options' => array(
						  	   'Approved' => 'Approved',
							   'Rejected' => 'Rejected'
						  )
					 ),
				 ));			 
		}
		
		for($i=1; $i <= $this->subordinateCount; $i++)
		{
			$this->add(array(
			   'name' => 'subordinate'.$i,
				'type'=> 'select',
					 'options' => array(
						 'empty_option' => 'Approve/Reject',
						 'class'=>'control-label',
					 ),
					 'attributes' => array(
						  'class' => 'form-control ',
						  'options' => array(
						  	   'Approved' => 'Approved',
							   'Rejected' => 'Rejected'
						  )
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
