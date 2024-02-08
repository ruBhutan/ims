<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class SearchForm extends Form
{
	protected $username;
	protected $organisation_id;
	protected $employee_id;
	protected $serviceLocator;
	
	public function __construct($serviceLocator = null, array $options = [])
    {
		parent::__construct('ajax', $options);

		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator;
		$this->ajax = $options;

		$authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
		// Use service locator to get the authPlugin
		$this->username = $authPlugin['username'];
		$this->organisation_id = $this->getOrganisationId($this->username);
		$this->employee_id = $this->getEmployeeId($this->username);
				
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));

		$this->add(array(
			'name' => 'assessment_component_id',
			 'type'=> 'select',
			  'options' => array(
				  'empty_option' => 'Please Select Academic Module',
								  'disable_inarray_validator' => true,
								  'class'=>'control-label',
				  ),
				  'attributes' => array(
					   'class' => 'form-control',
									   'id' => 'selectAcademicModule',
									   'options' => $this->createAcademicModule(),
				  ),
		 ));

		 $this->add(array(
			'name' => 'assessment_type',
			 'type'=> 'select',
			  'options' => array(
				  'empty_option' => 'Please Select Assessment Type',
								  'disable_inarray_validator' => true,
								  'class'=>'control-label',
			  ),
			  'attributes' => array(
				   'class' => 'form-control',
								   'id' => 'selectAssessmentType',
								   'options' => array(),
			  ),
		  ));
	 
		 $this->add(array(
			'name' => 'section',
			 'type'=> 'select',
			  'options' => array(
				  'empty_option' => 'Please Select Section',
								  'disable_inarray_validator' => true,
								  'class'=>'control-label',
			  ),
			  'attributes' => array(
				   'class' => 'form-control',
			  ),
		  ));
		 
		/*
		* The following is without AJAX
		$this->add(array(
			'name' => 'assessment_component_id',
			'type' => 'select',
			'options' => array(
                 'empty_option' => 'Please Select a Module',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Programme',
				),
		));
				
		$this->add(array(
			'name' => 'assessment_type',
			'type' => 'select',
			'options' => array(
                 'empty_option' => 'Please Select Assessment Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Batch',
				),
		));
		*/
		
		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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
	
	private function createAcademicModule()
    {
        //$semester = $this->getSemester();
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`module_title` AS `module_title`, `t3`.`module_tutor` AS `module_tutor` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_tutors` AS `t3` ON `t3`.`academic_modules_allocation_id` = `t1`.`id` WHERE t1.academic_year = "'.$academic_year.'" AND  t3.module_tutor LIKE "%'.$this->username.'%"';
		
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();
		
		foreach ($result as $res) {
            $selectData[$res['id']] = $res['module_title'];
        }
        return $selectData;
    }
	
	private function getOrganisationId($username)
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $organisationId = $res['organisation_id'];
        }
        return $organisationId;
    }
	
	private function getEmployeeId($username)
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $employee_id = $res['id'];
        }
		
        return $employee_id;
    }
	
	public function getSemester()
	{
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		$sql = 'SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE from_date <= "'.date('Y-m-d').'" AND to_date >= "'.date('Y-m-d').'"  AND t2.organisation_id = '.$this->organisation_id;
		$statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
		
		$semester = NULL;
		
		/*foreach($result as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$semester = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$semester = 'Spring';
			}
		}*/
		foreach($result as $set){
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester['academic_event'] = 'Autumn';
                $semester['academic_year'] = $set['academic_year'];
			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester['academic_event'] = 'Spring';
                $semester['academic_year'] = $set['academic_year'];
			}
		}
		return $semester;
	}
	
	private function getAcademicYear($semester_type)
	{
		$academic_year = NULL;
		if($semester_type == 'Autumn'){
			$academic_year = date('Y').'-'.(date('Y')+1);
		} else {
			$academic_year = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
}