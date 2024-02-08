<?php

namespace Examinations\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ExamDeclarationForm extends Form
{

	public function __construct()
     {
		parent::__construct();
		
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
				'type' => 'Hidden',
		));
		
		$this->add(array(
				'name' => 'organisation_id',
				'type' => 'Hidden',
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
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));
		
		$this->add(array(
			'name' => 'declare',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Declare Current Academic Year Results',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		)); 
          
	}
}
