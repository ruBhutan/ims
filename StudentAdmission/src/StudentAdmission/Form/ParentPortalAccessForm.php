<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class ParentPortalAccessForm extends Form
{
	public function __construct()
	{

		// we want to ignore the name passed
        parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));

		$this->add(array(
            'name' => 'parent_cid',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
                ),
            'attributes' =>array(
                'class' => 'form-control',
                'placeholder' => 'Student Name',
                ),
        ));

        $this->add(array(
            'name' => 'student_id',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
                ),
            'attributes' =>array(
                'class' => 'form-control',
                'placeholder' => 'Student Name',
                ),
        ));

        $this->add(array(
            'name' => 'parent_type',
            'type'=> 'Select',
            'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Semester',
                'value_options' => array(
                    '0' => 'Select',
                    'Father' => 'Father',
                    'Mother' => 'Mother',
                    'Guardian' => 'Guardian',
                )
            ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => 'required'
            ),
        ));           
	}
}