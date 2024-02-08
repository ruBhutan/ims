<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EditAssessmentMarkForm extends Form
{
    protected $assessmentMarks;
	
	public function __construct($assessment_marks)
     {
        parent::__construct('markentry');
		
        $this->assessmentMarks = $assessment_marks;
        		
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
		 $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));
        				
		$this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          		 
		 $this->add(array(
           'name' => 'marks',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
				  'min' => 0.0,
				  'max' => $this->assessmentMarks,
				  'step' => 0.01
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

         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                        'timeout' => 1800
                )
             )
         ));

		
         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Marks',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
}
