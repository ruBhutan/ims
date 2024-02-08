<?php

namespace RecheckMarks\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class RecheckChangeMarksForm extends Form
{
	protected $studentCount;
    //protected $assessmentMarks;
	
	public function __construct($studentCount)
     {
        parent::__construct('recheckmarks');
		
		$this->studentCount = $studentCount;
              //  $this->assessmentMarks = $assessment_marks;*/
        		
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
            'name' => 'academic_module',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'type',
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
                      'max' => 100,
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
					'value' => 'Update Marks',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
}
