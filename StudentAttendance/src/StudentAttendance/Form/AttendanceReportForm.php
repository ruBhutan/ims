<?php

namespace StudentAttendance\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class AttendanceReportForm extends Form
{
	protected $username;
	protected $organisation_id;
  	protected $serviceLocator;
  	protected $start_date;
	protected $end_date;
	
	public function __construct($serviceLocator = null, array $options = [])
     {
        
		parent::__construct('ajax', $options);
		 
		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
                $this->ajax = $options;
		
		//the following are so that we can get the organisation id
		$authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
		$this->organisation_id = $this->getOrganisationId($this->username);
		$this->start_date = $this->getStartDate($this->organisation_id);
		$this->end_date = $this->getEndDate($this->organisation_id);
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
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
                  'class' => 'form-control',
				  'id' => 'selectProgrammeAttendanceRecord',
				  'required' => true,
				  'options' => $this->getProgrammeList(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'module',
			 'type'=> 'select',
			  'options' => array(
				  'empty_option' => 'Please Select a Module',
				  'disable_inarray_validator' => true,
				  'class'=>'control-label',
			  ),
			  'attributes' => array(
				   'class' => 'form-control',
				   'id' => 'selectModuleAttendanceRecord',
				   'required' => true,
				   'options' => array(),
			  ),
		  ));
		 
		 $this->add(array(
			'name' => 'section',
			 'type'=> 'select',
			  'options' => array(
				  'empty_option' => 'Select Section',
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
			'name' => 'from_date',
			'type' => 'date',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control datepicker',
				//'min' => date('Y-m-d'),
				'max' => date('Y-m-d')
				),
		));
		
		$this->add(array(
			'name' => 'to_date',
			'type' => 'date',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control datepicker',
				//'min' => date('Y-m-d'),
				'max' => date('Y-m-d')
				),
		));*/

		$this->add(array(
			'name' => 'from_date',
			'type' => 'date',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control datepicker',
				// uncomment
			//	'min' => $this->start_date,
//             	'max' => $this->end_date,
				),
		));
		
		$this->add(array(
			'name' => 'to_date',
			'type' => 'date',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control datepicker',
				//uncomment
				//'min' => $this->start_date,
             	//'max' => $this->end_date,
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
	
	private function getProgrammeList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`programme_name` AS `programme_name` FROM `programmes` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['programme_name'];
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

    public function getSemester()
	{
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		$sql = 'SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE from_date <= "'.date('Y-m-d').'" AND to_date >= "'.date('Y-m-d').'"  AND t2.organisation_id = '.$this->organisation_id;
		$statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
		
		$semester = NULL;
		
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
        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		//$academic_year = NULL;

		var_dump($academic_year); die();
		
		if($semester == 'Autumn'){
			$academic_year; // = (date('Y')).'-'.(date('Y')+1);
		} else {
			$academic_year; // = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
	
	private function getStartDate($organisation_id)
	{
		//$semester = $this->getSemester();
		$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];

		$start_date = NULL;
		
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`from_date` AS `from_date`, `t2`.`organisation_id` AS `organisation_id` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE `t2`.`organisation_id` ="'. $organisation_id.'" AND `t2`.`academic_event` ="'.$semester.' Academic Session Duration" AND `t1`.`academic_year` ="'. $academic_year.'"';
		
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();

        foreach ($result as $res) {
            $start_date = $res['from_date'];
        }
        //var_dump($start_date); die();
        return $start_date;
	}
	
	private function getEndDate($organisation_id)
	{
		//$semester = $this->getSemester();
		$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];

        $end_date = NULL;
		
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`to_date` AS `to_date`, `t2`.`organisation_id` AS `organisation_id` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE `t2`.`organisation_id` ="'. $organisation_id.'" AND `t2`.`academic_event` ="'.$semester.' Semester Duration" AND `t1`.`academic_year` ="'. $academic_year.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();

        foreach ($result as $res) {
            $end_date = $res['to_date'];
        }
        
        //var_dump($end_date); die();
        return $end_date;
	}
}
