<?php

namespace JobPortal\Form;

use JobPortal\Model\LanguageSkills;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class LanguageFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('language');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new LanguageSkills());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
			'name' => 'language',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'required' => true,
				),
		));

		$this->add(array(
			'name' => 'spoken',
            'type'=> 'select',
             'options' => array(
                  'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Rating',
                 'value_options' => array(
                 	'Excellent' => 'Excellent',
                 	'Very Good' => 'Very Good',
                 	'Good' => 'Good',
                 	'Average' => 'Average',
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));

		$this->add(array(
			'name' => 'writing',
            'type'=> 'select',
             'options' => array(
                  'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Rating',
                 'value_options' => array(
                 	'Excellent' => 'Excellent',
                 	'Very Good' => 'Very Good',
                 	'Good' => 'Good',
                 	'Average' => 'Average',
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));


		$this->add(array(
			'name' => 'reading',
            'type'=> 'select',
             'options' => array(
                  'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Rating',
                 'value_options' => array(
                 	'Excellent' => 'Excellent',
                 	'Very Good' => 'Very Good',
                 	'Good' => 'Good',
                 	'Average' => 'Average',
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));
				
		$this->add(array(
			'name' => 'job_applicant_id',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));

        $this->add(array(
            'name' => 'last_updated',
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