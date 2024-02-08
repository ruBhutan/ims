<?php

namespace Planning\Form;

use Planning\Model\KeyAspiration;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class KeyAspirationFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('keyaspiration');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new KeyAspiration());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'financial_year',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'key_performance_indicator',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));
          
         $this->add(array(
           'name' => 'unit',
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
           'name' => 'outstanding',
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
           'name' => 'very_good',
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
           'name' => 'good',
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
           'name' => 'need_improvement',
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
           'name' => 'activity_status',
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
           'name' => 'employee_details_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'disable_inarray_validator' => true,
				 'value_options' => array(
				 )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'required' => true
             ),
         ));
		 
		  $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add New Key Aspiration',
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