<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class ElectiveModuleSearchForm extends Form
{
	protected $sectionList;
    protected $serviceLocator;
	
	public function __construct($serviceLocator = null, array $options = [])
     {
        parent::__construct('electivemodulesearch');
         
        $this->serviceLocator = $serviceLocator; 
		
		$authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
		$this->organisation_id = $this->getOrganisationId($this->username);
				
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
		
		$this->add(array(
           'name' => 'programmes_id',
           'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Programme',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  //'id' => 'selectProgrammeNameForAssessment',
				  'id' => 'selectElectiveProgrammeName',
				  'required' => true,
				  'options' => $this->createProgrammeList(),
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'academic_modules_allocation_id',
           'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Module',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  //'id' => 'selectModuleNameForAssessment',
				  'id' => 'selectElectiveModuleName',
				  'required' => true,
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'section_id',
           'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Section',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectElectiveSection',
				  'required' => true,
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
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));
	}
	
	private function getOrganisationId($username)
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id = '$username'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $organisationId = $res['organisation_id'];
        }
        return $organisationId;
    }
	
	private function createProgrammeList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, programme_name FROM programmes where organisation_id ="'.$this->organisation_id.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['programme_name'];
        }
        return $selectData;
    }
	
	private function getAcademicYear()
	{
		//$semester = $this->getSemester($this->organisation_id);
		$academic_event_details = $this->getSemester($this->organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		
		/*$academic_year = NULL;
		
		if($semester == 'Autumn'){
			$academic_year = date('Y').'-'.(date('Y')+1);
		} else {
			$academic_year = (date('Y')-1).'-'.date('Y');
		}
		*/
		return $academic_year;
	}
	
	private function getSemester($organisation_id)
	{
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE t1.from_date <="'.date('Y-m-d').'" AND t1.to_date >="'.date('Y-m-d').'" AND `t2`.`organisation_id` = "'.$organisation_id.'"';
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
}