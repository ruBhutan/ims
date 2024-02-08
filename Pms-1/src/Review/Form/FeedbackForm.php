<?php

namespace Review\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class FeedbackForm extends Form
{
	protected $elementCount;
	
	public function __construct($elementCount)
     {
        $this->elementCount = $elementCount; 
		
		parent::__construct('evaluation');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left radio',
        ));
		
		$this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
        
		$this->add(array(
             'name' => 'employee_details_id',
              'type' => 'Hidden'  
         ));
		
		$this->add(array(
             'name' => 'appraisal_period',
              'type' => 'Hidden'  
         ));  		 
		 
		
		for($i=1; $i <= $this->elementCount; $i++)
		{
			$this->add(array(
			   'name' => 'evaluation'.$i,
			   'type'=> 'Radio',
			   'options' => array(
					 'class' => 'flat',
					 'value_options' => array(
                                             '5' => 'Excellent   .',
                                             '4' => 'Very Good   .',
                                             '3' => 'Good   .',
                                             '2' => 'Poor   .',
                                             '1' => 'Very Poor   .',
					 )
				 ),
			 ));
			 
			 $this->add(array(
			   'name' => 'remarks_'.$i,
				'type'=> 'Text',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control ',
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
