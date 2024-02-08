<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentType;
use Zend\Form\Fieldset;
use zend\InputFilter\InputFilter;
use zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentTypeFieldset extends Fieldset implements InputFilterProviderInterface
{
  protected $inputFilter;

	public function __construct()
     {

         // we want to ignore the name passed
        parent::__construct('studenttype');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudentType());
         
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
           'name' => 'student_type',
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

     //Add content to these methods:
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
      throw new \Exception("Not used");  
     }

     public function getInputFilter()
     {
      if(!$this->inputFilter)
      {
        $inputFilter = new InputFilter();

        $inputFilter->add(array(
          'name' => 'category_type',
          'required' => true,
          'filters' => array(
            array('name' => 'StripTags'),
            array('name' => 'StringTrim'),
          ),
          'validators' => array(
            array(
              'name' => 'StringLength',
              'min' => 1,
              'max' => 45,
            ),
            ),
          ));

        $inputFilter->add(array(
          'name' => 'description',
          'required' => false,
          'filters' => array(
            array('name' => 'StripTags'),
            array('name' => 'StringTrim'),
            ),
          'validators' => array(
            array(
              'name' => 'StringLength',
              'min' => 1,
              'max' => 45,
              ),
            ),
          ));
      }
     }
}