<?php

namespace Programme\Form;

use Programme\Model\EditAssessmentMark;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EditAssessmentMarkFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('assessmentmark');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EditAssessmentMark());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          		 
		 $this->add(array(
           'name' => 'assessment_marks',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add New EditAssessmentMark',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'search',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  
		  $this->add(array(
			'name' => 'update',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Update Edited Assessment Mark',
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