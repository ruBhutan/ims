<?php

namespace HrSettings\Form;

use HrSettings\Model\PositionCategory;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class PositionCategoryFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('positioncategory');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new PositionCategory());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         //category
		 $this->add(array(
           'name' => 'category',
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
           'name' => 'description',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'major_occupational_group_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Occupational Group',
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