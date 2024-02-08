<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class StudentSearchForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
               
                $this->add(array(
			'name' => 'student_name',
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
				'placeholder' => 'Student ID',
				),
		));
                
                $this->add(array(
			'name' => 'programme',
			'type'=> 'select',
                        'options' => array(
                            'empty_option' => 'Please Select a Programme',
                            'disable_inarray_validator' => true,
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                             'class' => 'form-control ',
                        ),
                    ));
                
                $this->add(array(
			'name' => 'academic_year',
			'type'=> 'select',
                        'options' => array(
                            'empty_option' => 'Please Select an Academic Year',
                            'disable_inarray_validator' => true,
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                             'class' => 'form-control ',
                        ),
                    ));
		
		$this->add(array(
			'name' => 'year',
			'type'=> 'select',
                        'options' => array(
                            'empty_option' => 'Please Select',
                            'disable_inarray_validator' => true,
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                             'class' => 'form-control ',
                        ),
                    ));

		/*$this->add(array(
            'name' => 'section',
             'type'=> 'Select',
              'options' => array(
                 'empty_option' => 'Please Select Section',
                  'class'=>'control-label',
                  'disable_inarray_validator' => true,

              ),
              'attributes' => array(
                   'class' => 'form-control ',

              ),
          )); */
		
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