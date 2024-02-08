<?php

namespace JobPortal\Form;

use JobPortal\Model\ApplicantMarks;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ApplicantMarksFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('applicantmarks');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ApplicantMarks());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
			'name' => 'x_english',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter Class X English Marks --',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
			
		));
		
		$this->add(array(
			'name' => 'x_sub1_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 10 Best one mark-- ',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));
		
		$this->add(array(
			'name' => 'x_sub2_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 10 Best two mark-- ',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
				),
		));
	   
		$this->add(array(
			'name' => 'x_sub3_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 10 Best three mark-- ',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));

		$this->add(array(
			'name' => 'x_sub4_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '----Class 10 Best three mark--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => false,
			),
		));
		
		$this->add(array(
			'name' => 'xll_english',
			'type' => 'number',
			'options' => array(
                 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 English Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));

		$this->add(array(
			'name' => 'xll_sub1_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 Best One Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));
		
		$this->add(array(
			'name' => 'xll_sub2_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 Best Two Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => true,
			),
		));
		
		$this->add(array(
			'name' => 'xll_sub3_mark',
			'type' => 'number',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Class 12 Best Three Marks--',
				'min' => 0.0,
				'step' => 0.01,
				'required' => false,
			),
		));
		
		$this->add(array(
			'name' => 'job_applicant_id',
			'type' => 'Text',
			'options' => array(
                 'class'=>'control-label',

             ),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'last_updated',
			'type' => 'Text',
			'options' => array(
                 'class'=>'control-label',
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
				'value' => 'Add Marks',
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
		return [
            [
                'name'       => 'x_english',
                'required'   => false,
			],
			[
                'name'       => 'x_sub1_mark',
                'required'   => false,
			],
			[
                'name'       => 'x_sub2_mark',
                'required'   => false,
			],
			[
                'name'       => 'x_sub3_mark',
                'required'   => false,
			],
			[
                'name'       => 'x_sub4_mark',
                'required'   => false,
			],
			[
                'name'       => 'xll_english',
                'required'   => false,
			],
			[
                'name'       => 'xll_sub1_mark',
                'required'   => false,
			],
			[
                'name'       => 'xll_sub2_mark',
                'required'   => false,
            ],
            [
                'name'       => 'xll_sub3_mark',
                'required'   => false,
            ]
        ];
     }
}