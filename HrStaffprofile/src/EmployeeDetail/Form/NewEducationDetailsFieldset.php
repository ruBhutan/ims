<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

//use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewEducationDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeefields');
		
		$this->setHydrator(new ClassMethods(false));
		//Model not used as we are going to extract the values at the controller
		//$this->setObject(new NewEmployeeDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-group has-feedback',
                ));
	 
		 $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'study_level',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Study Level',
				 'class' => 'control-label',
				 'value_options' => array(
				 		'Class VIII' => 'Class VIII',
						'Class X' => 'Class X',
						'Class XII' => 'Class XII',
						'Certificate' => 'Certificate',
						'Diploma' => 'Diploma',
						'Advanced Diploma' => 'Advanced Diploma',
						'Bacherlor (General)' => 'Bacherlor (General)',
						'Bacherlor (Honours)' => 'Bacherlor (Honours)',
						'Bacherlor (Technical)' => 'Bacherlor (Technical)',
						'Post Graduate Certificate' => 'Post Graduate Certificate',
						'Post Graduate Diploma' => 'Post Graduate Diploma',
						'Masters' => 'Masters',
						'M.Phil' => 'M.Phil',
						'Doctrate (PhD)' => 'Doctrate (PhD)',
						'Post Doctrate (PhD)' => 'Post Doctrate (PhD)',
						'Others' => 'Others'
				 ),
             ),
             'attributes' => array(
                  'class' => 'control-label',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'study_field',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'Field',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'religion',
			'type' => 'Select',
			'options' => array(
                 'empty_option' => 'Select a Religion',
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
		));
		
		$this->add(array(
			'name' => 'blood_group',
			'options' => array(
                 'empty_option' => 'Select a Blood Group',
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
		));
		 
		 $this->add(array(
           'name' => 'study_mode',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Mode of Study',
				 'class' => 'control-label',
				 'value_options' => array(
				 		'Full Time on Campus' => 'Full Time on Campus',
						'Mixed Mode' => 'Mixed Mode',
						'Part-Time' => 'Part-Time'
				 )
             ),
             'attributes' => array(
                  'class' => 'control-label',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_name',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'College Name',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_city',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'City',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_country',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Country',
             ),
         ));
		 		 		 
		 $this->add(array(
           'name' => 'start_date',
            'type'=> 'date',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Start Date',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'end_date',
            'type'=> 'date',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Completion Date',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'marks_obtained',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Agg Marks',
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