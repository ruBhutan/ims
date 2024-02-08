<?php

namespace Administration\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class PasswordSearchForm extends Form
{	
	public function __construct()
     {
		parent::__construct('password');
		         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
				 
		 $this->add(array(
           'name' => 'name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'user_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'user_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Type',
				 'class'=>'control-label',
				 'value_options' => array(
				 	'Staff' => 'Staff',
					'Student' => 'Student'
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));

         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search User ',
					'id' => 'submitbutton',
                    'class' => 'btn btn-success',
				),
		  ));
     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'name' => array(
                 'required' => false,
             ),
         );
     }
}