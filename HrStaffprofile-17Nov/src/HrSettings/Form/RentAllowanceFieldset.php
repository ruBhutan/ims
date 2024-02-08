<?php

namespace HrSettings\Form;

use HrSettings\Model\RentAllowance;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class RentAllowanceFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('rentallowance');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new RentAllowance());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          		 
		 //rent allowance
		 $this->add(array(
           'name' => 'rent_allowance',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'position_level',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Position Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
          
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Save',
					'id' => 'submitbutton',
                    'class' => 'btn btn-success',
					),
				
				));
           $this->add(array(
				'name' => 'cancel',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Cancel',
					'id' => 'submitbutton',
                    'class' => 'btn btn-danger',
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