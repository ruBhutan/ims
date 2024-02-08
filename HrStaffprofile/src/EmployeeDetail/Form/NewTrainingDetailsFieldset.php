<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

//use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewTrainingDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeefields');
		
		$this->setHydrator(new ClassMethods(false));
		//Model not used as we are going to extract the values at the controller
		//$this->setObject(new NewEmployeeDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));
	 
		 $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'course_title',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'Course Title',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'institute_name',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Institute Name',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'institute_location',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Institute Location & Address',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'training_start_date',
            'type'=> 'date',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'From',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'course_level',
            'type'=> 'text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Course Level',
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