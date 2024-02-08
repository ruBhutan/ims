<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class StdChangeProgrammeSearchForm extends Form
{
	public function __construct()
	{

		// we want to ignore the name passed
        parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));

		$this->add(array(
            'name' => 'programme',
            'type'=> 'Select',
            'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Programme',
                'value_options' => array(
                    '0' => 'Select'
                )
            ),
           'attributes' => array(
                'class' => 'form-control',
                'value' => '0', // Set selected to 0
                '//required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'year',
            'type'=> 'Select',
            'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Year',
                'value_options' => array()
            ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => 'required'
            ),
        ));
		
		$this->add(array(
			'name' => 'studentName',
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
			'name' => 'studentId',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Student ID',
				),
		));
		
		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 600
                )
             )
         ));
                
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));            
	}
}