<?php

namespace Examinations\Form;

use Examinations\Model\ExaminationCode;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ExaminationCodeFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('examinations');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ExaminationCode());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

        $this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
			'name' => 'student_id',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
              
		$this->add(array(
			'name' => 'examination_code',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
				
		$this->add(array(
			'name' => 'code_date',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'academic_module_code',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'academic_modules_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'programme_code',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'programmes_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
				
		$this->add(array(
			'name' => 'organisation_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		            
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Submit',
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