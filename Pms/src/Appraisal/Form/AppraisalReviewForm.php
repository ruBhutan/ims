<?php

namespace Appraisal\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AppraisalReviewForm extends Form
{
	protected $appraisalCount;
	
	public function __construct($appraisalCount)
     {
        parent::__construct('appraisals');
        
		$this->appraisalCount = $appraisalCount;
				
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        foreach($this->appraisalCount as $count)
		{
			$this->add(array(
			   'name' => 'remarks'.$count,
				'type'=> 'textarea',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
					  'rows' => 3,
				 ),
			 ));
			 
			 $this->add(array(
			   'name' => 'status'.$count,
				'type'=> 'select',
					 'options' => array(
						 'empty_option' => 'Status',
						 'class'=>'control-label',
					 ),
					 'attributes' => array(
						  'class' => 'form-control ',
						  'options' => array(
						  	   'Approved' => 'Approved',
							   //'Approve Conditional to Changes' => 'Approve Conditional to Changes',
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
