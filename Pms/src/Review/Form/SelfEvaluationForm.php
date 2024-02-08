<?php

namespace Review\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class SelfEvaluationForm extends Form
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
            'class' => 'form-horizontal form-label-left',
        ));
		
		foreach($this->elementCount as $count)
		{
			$this->add(array(
			   'name' => 'evaluation'.$count,
				'type'=> 'select',
					 'options' => array(
						 'empty_option' => 'Evaluation',
						 'class'=>'control-label',
					 ),
					 'attributes' => array(
						  'class' => 'form-control ',
						  'options' => array(
						  		'1' => 'Needs Improvement',
								'2' => 'Good',
								'3' => 'Very Good',
								'4' => 'Excellent'
						  ),
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
