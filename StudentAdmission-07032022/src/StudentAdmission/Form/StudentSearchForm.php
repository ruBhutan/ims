<?php

namespace StudentAdmission\Form;

use Zend\Form\Form;

class StudentSearchForm extends Form
{
	protected $username;
	protected $organisation_id;
	protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
	{
		 // we want to ignore the name passed
		parent::__construct('ajax', $options);

		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        	$this->ajax = $options;
		
		//the following are so that we can get the organisation id
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
       		$this->username = $authPlugin['username'];

		$this->organisation_id = $this->getOrganisationId($this->username);
		
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
			'name' => 'cid',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'CID',
				),
		));
		
		$this->add(array(
	           'name' => 'student_status_type',
	            'type'=> 'select',
	             'options' => array(
	                 'empty_option' => 'Please Select a Student Status',
					 'disable_inarray_validator' => true,
					 'class'=>'control-label',
	             ),
	             'attributes' => array(
	                  'class' => 'form-control',
					 // 'id' => 'selectProgrammeByTutor',
					  'options' => $this->getStudentStatusList(),
					  'required' => 'required',
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
	private function getStudentStatusList()
	{
	        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
	        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`reason` AS `student_status` FROM `student_status_type` AS `t1`';
	        $statement = $dbAdapter->query($sql);
	        $result    = $statement->execute();
	        $selectData = array();

	       foreach ($result as $res) {
	            $selectData[$res['id']] = $res['student_status'];
	        }
	        return $selectData;
	}
		
	private function getOrganisationId($username)
	{
	        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
	        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'"';
	        $statement = $dbAdapter->query($sql);
	        $result    = $statement->execute();

	       foreach ($result as $res) {
	            $organisationId = $res['organisation_id'];
	        }

	        return $organisationId;
	}
}
