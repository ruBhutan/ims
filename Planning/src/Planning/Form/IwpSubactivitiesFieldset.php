<?php

namespace ResearchApplication\Form;

use ResearchApplication\Model\IwpSubactivitiesFieldset;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class IwpSubactivitiesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('iwpsubactivities');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new IwpSubactivities());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'subactivity_name',
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
           'name' => 'outstanding_description',
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
           'name' => 'very_good_description',
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
           'name' => 'good_description',
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
           'name' => 'needs_improvement_description',
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
           'name' => 'emp_iwp_rating',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         )); 
		 
		 $this->add(array(
           'name' => 'rated_by',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         )); 
		 
		 $this->add(array(
           'name' => 'remarks',
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
            'type'=> 'Text',
             'options' => array(
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