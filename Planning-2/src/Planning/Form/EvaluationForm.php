<?php

namespace Planning\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EvaluationForm extends Form
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
		
		foreach($this->elementCount as $key=>$value)
		{
			$this->add(array(
			   'name' => 'evaluation_'.$key,
				'type'=> 'Textarea',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control ',
                                          'required' => true,
					  'rows' => 5
				 ),
			 ));
			 
			 $this->add(array(
			   'name' => 'status_'.$key,
				'type'=> 'Textarea',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control ',
                                          'required' => true,
					  'rows' => 5
				 ),
			 ));
			 
			 $this->add(array(
			   'name' => 'verification_means_'.$key,
				'type'=> 'Textarea',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control ',
                                          'required' => true,
					  'rows' => 5
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
