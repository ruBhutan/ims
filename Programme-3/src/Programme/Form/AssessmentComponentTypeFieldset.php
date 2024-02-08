<?php

namespace Programme\Form;

use Programme\Model\AssessmentComponentType;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AssessmentComponentTypeFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('assessmentcomponent');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AssessmentComponentType());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'assessment_component_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Assessment Component Type',
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'options' => array(
						'Continuous Assessment' => 'Continuous Assessment',
						'Continuous Assessment (Theory)' => 'Continuous Assessment (Theory)',
						'Continuous Assessment (Practical)' => 'Continuous Assessment (Practical)',
						'Semester Exams' => 'Semester Exams',
						'Semester Exams (Theory)' => 'Semester Exams (Theory)',
                        'Semester Exams (Practicals)' => 'Semester Exams (Practical)'
					),
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'organisation_id',
           'type'=> 'text',
             'options' => array(
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
					'value' => 'Add New Assessment Component Type',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'search',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search',
					'id' => 'searchbutton',
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