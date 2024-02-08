<?php

namespace Job\Form;

use Job\Model\MajorOccupationalGroup;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class MajorOccupationalGroupFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('majoroccupationalgroup');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new MajorOccupationalGroup());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         //major occupational group
		 $this->add(array(
           'name' => 'major_occupational_group',
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