<?php

namespace Planning\Form;

use Planning\Model\Activities;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ActivitiesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('rubactivities');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Activities());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'activity_name',
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
           'name' => 'budget_overlay',
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
           'name' => 'funding',
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
           'name' => 'rub_objectives_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'disable_inarray_validator' => true,
				 'value_options' => array(
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