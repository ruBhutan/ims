<?php

namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EmployeeRubWorkExperienceForm extends Form
{
	protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
	{
		// we want to ignore the name passed
        parent::__construct('ajax', $options);

        $this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        $this->ajax = $options;
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

         $this->add(array(
             'type' => 'EmployeeDetail\Form\EmployeeRubWorkExperienceFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
			'name' => 'occupational_group',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Group',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectOccupationGroup',
				  'options' => $this->createOccupationalGroup(),
             ),
         ));
		 
		$this->add(array(
			'name' => 'position_category',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectCategory',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'position_title',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectPositionTitle',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'position_level',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectPositionLevel',
				  'options' => array(),
             ),
         ));

         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));
                
               
		/*
			
		$this->add(array(
			'name' => 'photograph',
			'type' => 'File',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		*/
		
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
			),
		));
                
	}


	private function createOccupationalGroup()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, major_occupational_group FROM major_occupational_group';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['major_occupational_group'];
        }
        return $selectData;
    }
}