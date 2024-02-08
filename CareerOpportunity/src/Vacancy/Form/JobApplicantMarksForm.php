<?php

namespace Vacancy\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class JobApplicantMarksForm extends Form
{

	public function __construct()
	{
		 // we want to ignore the name passed
        parent::__construct('jobapplicantmarks');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

         $this->add(array(
             'type' => 'Vacancy\Form\JobApplicantMarksFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));  
		
		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 1200
                )
             )
         ));		
	}
}
