<?php

namespace Administration\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class RoutesConfigurationForm extends Form
{
	protected $routesList;
	protected $userRouteList;
	
	public function __construct($routesList, $userRouteList)
     {
        
		parent::__construct('userroutes');
		
		$this->routesList = $routesList;
		$this->userRouteList = $userRouteList;
         
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
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'user_role_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Role',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		
		foreach($this->routesList as $key=>$value){
			foreach($value as $key2 => $value2){
				if(in_array($key2, $this->userRouteList))
					$selectedValue = $key2;
				else
					$selectedValue = 'no access';
				$this->add(array(
				'type'=> 'checkbox',
				'name' => 'route_'.$key.'_'.$key2,
				'options' => array(
					 'class' => 'flat',
					 'label' => $value2,
					 'use_hidden_element' => true,
					 'checked_value' => $key2,
					 'unchecked_value' => 'no access'
				 ),
				 'attributes' => array(
					  'class' => 'checkbox',
					  'value' => $selectedValue
				 ),
			 ));
			}
		}

         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));

         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add New User Route',
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