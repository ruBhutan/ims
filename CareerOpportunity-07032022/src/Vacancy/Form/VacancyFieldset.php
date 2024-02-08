<?php

namespace Vacancy\Form;

use Vacancy\Model\Vacancy;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class VacancyFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('vacancy');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Vacancy());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 		 
         $this->add(array(
           'name' => 'working_agency',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Working Agency',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Employee Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

      $this->add(array(
           'name' => 'area',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
         $this->add(array(
           'name' => 'position_title',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

         $this->add(array(
           'name' => 'additional_position_title',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'Placeholder' => 'Enter Additional Position Title (Optional)',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'position_category',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Category',
        				 'disable_inarray_validator' => true,
        				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'position_level',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Level',
        				 'disable_inarray_validator' => true,
        				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));

     $this->add(array(
           'name' => 'additional_position_level',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'Placeholder' => 'Enter Additional Position Level (Optional)',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'no_of_slots',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'main_purpose_of_the_position',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                 'rows' => 3
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'Placeholder' => "Enter Main purpose of the position"
             ),
         ));
		 
		 $this->add(array(
           'name' => 'general_responsibilities',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'specific_responsibilities',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'rows' => 3
             ),
         ));

     $this->add(array(
           'name' => 'minimum_study_level_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Position Level',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
		 
		  $this->add(array(
           'name' => 'education_qualification',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3
             ),
         ));
      $this->add(array(
           'name' => 'experience',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
          'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'vacancy_type',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'knowledge_skills',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3
             ),
         ));
		 
		  $this->add(array(
           'name' => 'date_of_advertisement',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
        				  'id' => 'single_cal3',
        				  'required' => true
             ),
         ));

      $this->add(array(
           'name' => 'last_time_submission',
            'type'=> 'time',
             'options' => array(
                 'class'=>'control-label',
         'format' => 'H:i'
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));

       $this->add(array(
           'name' => 'last_date_submission',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
          'id' => 'single_cal4',
          'required' => true
             ),
         ));

       $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control'
             ),
         ));

       $this->add(array(
           'name' => 'last_updated',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control'
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add New Vacancy',
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