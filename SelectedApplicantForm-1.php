<?php

namespace Vacancy\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class SelectedApplicantForm extends Form
{
	public function __construct($name = null, array $options = [])
     {
		 parent::__construct('ajax', $options);
		
		$this->adapter1 = $name; 
		$this->ajax = $name; 
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
             'type' => 'Vacancy\Form\SelectedApplicantFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));
		 
		 $this->add(array(
			'name' => 'departments_units_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Unit',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectUnits',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'organisation_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select an Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectOrganisationName',
				  'options' => $this->createOrganisationName(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'departments_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Department',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectDepartments',
				  'options' => array(),
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
					'value' => 'Submit',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
	}
	
	private function createOrganisationName()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT id, organisation_name FROM organisation';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
    }
}