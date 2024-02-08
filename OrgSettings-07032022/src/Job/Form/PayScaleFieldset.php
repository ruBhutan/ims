<?php

namespace Job\Form;

use Job\Model\PayScale;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class PayScaleFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('payscale');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new PayScale());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
		 $this->add(array(
           'name' => 'minimum_pay_scale',
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
           'name' => 'increment',
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
           'name' => 'maximum_pay_scale',
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
           'name' => 'status',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Status',
				 'class'=>'control-label',
                 'value_options' => array(
                     'Active' => 'Active',
                     'Inactive' => 'Inactive'
                 )
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