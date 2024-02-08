<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StdPreviousSchoolDetails;
//use StudentAdmission\Model\UpdateReportedStudentDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StdPreviousSchoolDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('stdpreviousschooldetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StdPreviousSchoolDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
               
         $this->add(array(
             'type' => 'Text',
             'name' => 'previous_institution', 
             'attributes' => array(
				 'class' => 'control-label col-md-5 col-sm-5 col-xs-12',
                 'placeholder' => 'Previous School',
                 'required' => 'required',
              ),   
             'options' => array(
                      )
         ));
		 
		 $this->add(array(
             'type' => 'Text',
             'name' => 'aggregate_marks_obtained', 
             'attributes' => array(
                 'class' => 'control-label col-md-5 col-sm-5 col-xs-12',
				 'placeholder' => 'Marks Obtained',
              ),   
             'options' => array(
                      )
         ));
         
         $this->add(array(
             'type' => 'Zend\Form\Element\Date',
             'name' => 'from_date', 
             'attributes' => array(
                   'type' => 'Date',
                 'class' => 'control-label col-md-5 col-sm-5 col-xs-12',
              ),   
             'options' => array(
                     )
         ));

         $this->add(array(
             'type' => 'Zend\Form\Element\Text',
             'name' => 'to_date', 
             'attributes' => array(
                 'type' => 'Date',
                 'class' => 'control-label col-md-5 col-sm-5 col-xs-12',
              ),   
             'options' => array(
                     )
         ));
		 
		 $this->add(array(
             'type' => 'Text',
             'name' => 'previous_education', 
             'attributes' => array(
                 'class' => 'control-label col-md-5 col-sm-5 col-xs-12',
				 'placeholder' => 'Education Attended',
                 'required' => 'required',
              ),   
             'options' => array(
                      )
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