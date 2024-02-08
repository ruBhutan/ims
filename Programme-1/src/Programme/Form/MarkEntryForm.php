<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class MarkEntryForm extends Form
{
	protected $studentCount;
    protected $assessmentMarks;
	
	public function __construct($studentCount, $assessment_marks)
     {
        parent::__construct('markentry');
		
		$this->studentCount = $studentCount;
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
		 
		 
		for($i=1; $i <= $this->studentCount; $i++)
		{
			$this->add(array(
				'name' => 'marks_'.$i,
				'type'=> 'number',
				'options' => array(
					 'class'=>'control-label',
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
					  'required' => true,
					  'min' => 0.0,
					  'max' => $this->assessmentMarks,
					  'step' => 0.01,
                      'value' => 0
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
     }
}
