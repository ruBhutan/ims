<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class NewStudentSearchForm extends Form
{
	protected $serviceLocator;
	
	public function __construct($serviceLocator = null, array $options = [])
	{

		// we want to ignore the name passed
        parent::__construct('ajax', $options);

        $this->serviceLocator = $serviceLocator;
        $this->ajax = $serviceLocator;
        $this->ajax = $options;
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));


		$this->add(array(
			'name' => 'organisation',
			'type' => 'Select',
			'options' => array(
				'empty_option' => 'Please Select organisation',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
				),
			'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectStdRegisterOrganisation',
                  'options' => $this->createStdRegisterOrganisation(),
                  'required' => 'required'
             ),
		));

		$this->add(array(
			'name' => 'programme',
			'type' => 'Select',
			'options' => array(
				'empty_option' => 'Please Select Programme',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
				),
			'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectselectStdRegisterProgramme',
                  'options' => array(),
                  'required' => 'required'
             ),
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
			'name' => 'cid',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Citizenship ID No',
				),
		));

		$this->add(array(
			'name' => 'admission_year',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Admission Year',
				),
		));
  
        $this->add(array(
			'name' => 'gender',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Gender',
				),
		));

		$this->add(array(
			'name' => 'student_reporting_status',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Type Reporting Status',
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

	private function createStdRegisterOrganisation()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, organisation_name FROM organisation';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
    }
}