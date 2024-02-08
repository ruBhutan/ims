<?php

namespace Administration\Form;

use Administration\Model\Password;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserPasswordFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('changeuserpassword');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Password());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'user_type_id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
           'name' => 'old_password',
            'type'=> 'password',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'old_password',
             ),
         )); 
		 		 
		$this->add(array(
           'name' => 'password',
            'type'=> 'password',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
				          'id' => 'password',
             ),
         )); 
		 
		 $this->add(array(
           'name' => 'repeat_password',
            'type'=> 'password',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
				          'id' => 'password2',
             ),
         ));

     $this->add(array(
           'name' => 'sign_in',
            'type'=> 'checkbox',
             'options' => array(
                'class'=>'control-label',
                'use_hidden_element' => true,
                'checked_value' => '1',
                ),
            'attributes' => array(
                'class' => 'flat',
                'value' => 'no',
            ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Change Password',
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