<?php

namespace Review\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AcademicSelfEvaluationForm extends Form
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
				'type'=> 'number',
					 'options' => array(
						 'class'=>'control-label',
					 ),
					 'attributes' => array(
						  'class' => 'form-control',
						  'required' => true
					 ),
				 ));
			
			$this->add(array(
			   'name' => 'review'.$count,
				'type'=> 'textarea',
				 'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
					  'rows' => 3,
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
