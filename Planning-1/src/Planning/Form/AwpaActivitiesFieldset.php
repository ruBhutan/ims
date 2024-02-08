<?php

namespace Planning\Form;

use Planning\Model\AwpaObjectives;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AwpaActivitiesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('awpaactivities');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AwpaObjectives());
         
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
           'name' => 'activity_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          
         $this->add(array(
           'name' => 'awpa_objectives_activity_id',
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
           'name' => 'weight',
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
           'name' => 'excellent_rating',
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
           'name' => 'very_good_rating',
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
           'name' => 'good_rating',
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
           'name' => 'fair_rating',
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
           'name' => 'poor_rating',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'required' => true,
                 'value'=>'-'
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
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
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
					'value' => 'Add New Activity',
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