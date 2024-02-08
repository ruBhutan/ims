<?php

namespace CharacterCertificate\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CharacterEvaluationForm extends Form
{
	protected $studentCount;
	protected $criteriaCount;
	
	public function __construct($studentCount, $criteriaCount)
     {
        parent::__construct('characterevaluation');
		
		$this->studentCount = $studentCount;
		$this->criteriaCount = $criteriaCount;
        		
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        		
        $this->setAttributes(array(
            'class' => 'radio',
        ));
		
		$this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

        $this->add(array(
             'name' => 'did',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'programme_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'batch',
              'type' => 'Hidden'  
         ));

         $this->add(array(
         	'name' => 'academic_module_tutors_id',
         	'type' => 'Hidden',
         ));

         $this->add(array(
         	'name' => 'character_evaluation_criteria_id',
         	'type' => 'Hidden',
         ));
		 
		 $this->add(array(
             'name' => 'studentName',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'studentCount',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'criteriaCount',
              'type' => 'Hidden'  
         ));
		 
		for($i=1; $i <= $this->studentCount; $i++)
		{
			for($j=1; $j<=$this->criteriaCount; $j++)
			{
				$this->add(array(
			   'name' => 'evaluation_'.$i.$j,
			   'type'=> 'number',
			   'options' => array(
					 'class'=>'control-label',
				 ),
			   	'attributes' => array(
                      'class' => 'form-control ',
                      'required' => true,
                      'min' => 0.00,
                      'max' => 4.00,
                      'step' => 0.01
            	),
			 ));
			 
			}
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
					'value' => 'Submit Evaluation',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
}