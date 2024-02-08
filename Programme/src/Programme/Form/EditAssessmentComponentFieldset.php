<?php
/*
* Separate form and model for editing as the "Edit Functionality" contains more fields
*/

namespace Programme\Form;

use Programme\Model\EditAssessmentComponent;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EditAssessmentComponentFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('assessmentcomponent');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EditAssessmentComponent());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'assessment',
           'type'=> 'text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control ',
             ),
         ));
         
		 $this->add(array(
           'name' => 'weightage',
           'type'=> 'text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'assessment_year',
           'type'=> 'text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control ',
             ),
         ));
         $this->add(array(
           'name' => 'assessment_component_types_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Assessment Component',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'academic_modules_id',
           'type'=> 'text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
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
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Assessment Component Type',
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