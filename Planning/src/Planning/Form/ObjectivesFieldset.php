<?php

namespace Planning\Form;

use Planning\Model\Objectives;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ObjectivesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('rubobjectives');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Objectives());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'remarks',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select Organization for',
                'disable_inarray_validator' => true,
                'class'=>'control-label',
                'value_options'=> array(
                    'ovc' => 'Office of the Vice Chancellor',
                    'college' => 'College'
                ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
          
         $this->add(array(
           'name' => 'objectives',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
				  'rows' => 3
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
           'name' => 'five_year_plan',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'readonly' => true
             ),
         ));
           
        /* $this->add(array(
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
           'name' => 'rub_vision_mission_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         )); 
		 */
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