<?php

namespace Planning\Form;

use Planning\Model\ObjectivesWeightage;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ObjectivesWeightageFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('ovcobjectiveweightage');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ObjectivesWeightage());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'rub_objectives_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                  'empty_option' => 'Please Select Objective',
                 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));

         $this->add(array(
           'name' => 'five_year_plan_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => true
             ),
         ));


		 
		 $this->add(array(
           'name' => 'weightage',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));

     $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => true
             ),
         ));

     $this->add(array(
           'name' => 'departments_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                  'empty_option' => 'Please Select Department',
                 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => true
             ),
         ));

     $this->add(array(
           'name' => 'financial_year',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => true
             ),
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