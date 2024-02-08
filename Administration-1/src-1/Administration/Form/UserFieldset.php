<?php

namespace Administration\Form;

use Administration\Model\User;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('user');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new User());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'username',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Staff',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		 
		$this->add(array(
           'name' => 'password',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         )); 
		 
		 $this->add(array(
           'name' => 'role',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Role',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'region',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Region',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add New User',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'update',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Update User',
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