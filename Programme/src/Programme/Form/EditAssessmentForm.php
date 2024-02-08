<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EditAssessmentForm extends Form
{
	protected $studentList;
    protected $assessmentMarks;
	
	public function __construct($studentList, $assessment_marks)
     {
        parent::__construct('markentry');
		
		$this->studentList = $studentList;
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
             'name' => 'assessment_component_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'assessment_type',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'programmes_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'continuous_assessment_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'batch',
              'type' => 'Hidden'  
         ));
                 
         $this->add(array(
             'name' => 'section',
              'type' => 'Hidden'  
         ));
		 		 
		 $this->add(array(
             'name' => 'studentCount',
              'type' => 'Hidden'  
         ));
		 
		 
		foreach($this->studentList as $key => $value)
		{
			$this->add(array(
				'name' => 'marks_'.$key,
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
		}

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

        $this->add(array(
            'name' => 'delete',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Delete',
                'id' => 'deletebutton',
                'class' => 'btn btn-success'
                ),
        ));
     }
}
