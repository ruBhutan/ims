<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class NewEmployeeEducationForm extends Form
{
	public function __construct()
	{
		// we want to ignore the name passed
        parent::__construct('employeefields');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

         $this->add(array(
             'type' => 'EmployeeDetail\Form\NewEmployeeEducationFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));
                
               
		/*
			
		$this->add(array(
			'name' => 'photograph',
			'type' => 'File',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		*/
		
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
			),
		));
                
	}
}