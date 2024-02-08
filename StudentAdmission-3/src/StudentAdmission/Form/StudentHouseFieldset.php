<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentHouse;
use Zend\Form\Fieldset;
use zend\InputFilter\InputFilter;
use zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentHouseFieldset extends Fieldset implements InputFilterProviderInterface
{
  protected $inputFilter;

	public function __construct()
     {

         // we want to ignore the name passed
        parent::__construct('studenthouse');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudentHouse());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));
		 
		 $this->add(array(
             'name' => 'organisation_id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));
          
         $this->add(array(
           'name' => 'house_name',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		 
	     $this->add(array(
           'name' => 'description',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows'=> 5,
             ),
         ));

       $this->add(array(
           'name' => 'last_updated',
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
					'value' => 'Add',
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