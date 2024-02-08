<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class AssignModuleForm extends Form
{
	protected $username;
	protected $programmes_id;
	protected $academic_modules_id;
	protected $organisation_id;
	protected $tutor_list;
	protected $sectionList;
    protected $serviceLocator;
	
	public function __construct($serviceLocator = null, $programmes_id, $academic_modules_id, array $options = [])
     {
		 parent::__construct('ajax', $options);
		 
		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        $this->ajax = $options;
		
		$authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
		$this->programmes_id = $programmes_id;
		$this->academic_modules_id = $academic_modules_id;
		$this->organisation_id = $this->getOrganisationId($this->username);
		$this->tutor_list = $this->getTutorList($this->programmes_id, $this->academic_modules_id);
		$this->sectionList = $this->getSectionList();
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
		 $this->add(array(
           'name' => 'year',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'readonly' => true,
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programmes_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'readonly' => true,
				  'required' => true
             ),
         ));
		 
		 foreach($this->tutor_list as $value){
			 foreach($value as $id=>$code){
				 foreach($this->sectionList as $key2=>$value2){
					$assigned_modules = $this->getAssignedModules($id);
					if(in_array($code, $assigned_modules) && array_key_exists($key2, $assigned_modules))
						$selectedValue = 1;
					else
						$selectedValue = 0;
					$this->add(array(
					'type'=> 'checkbox',
					'name' => 'section_'.$id.'_'.$code.'_'.$key2,
					'options' => array(
						 'class' => 'flat',
						 'label' => $value2,
						 'use_hidden_element' => true,
						 'checked_value' => '1',
						 'unchecked_value' => '0'
					 ),
					 'attributes' => array(
						  'value' => $selectedValue
					 ),
				   ));
				}
			 }
		 }
		 /*
		 //old
		 foreach($this->tutor_list as $value){
			 foreach($value as $id=>$code){
				 foreach($this->sectionList as $key2=>$value2){
					$this->add(array(
					'type'=> 'checkbox',
					'name' => 'section_'.$id.'_'.$code.'_'.$key2,
					'options' => array(
						 'label' => $value2,
						 'use_hidden_element' => true,
					 ),
				 ));
				}
			 }
		 } 
		 */
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Assign Module',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
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
	
	private function getTutorList($programmes_id, $academic_modules_id)
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT employee_details_id, academic_modules_id FROM modules_tutors_assignment where academic_modules_id ="'.$academic_modules_id.'" AND programmes_id ="'.$this->programmes_id.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[][$res['employee_details_id']] = $res['academic_modules_id'];
        }
        return $selectData;
    }
	
	private function getSectionList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT * FROM `student_section`";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $sections[$res['id']] = $res['section'];
        }
        return $sections;
    }
	
	private function getAssignedModules($employee_id)
    {
		$academic_year = $this->getAcademicYear();
		$employee_username = $this->getEmployeeUserName($employee_id);
		
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`section` AS `section`, `t2`.`academic_modules_id` AS `academic_modules_id` FROM `academic_module_tutors` AS `t1` INNER JOIN `academic_modules_allocation` AS `t2` ON `t1`.`academic_modules_allocation_id` = `t2`.`id` WHERE t2.academic_modules_id ="'.$this->academic_modules_id.'" AND t2.academic_year ="'.$academic_year.'" AND `t1`.`module_tutor` = "'.$employee_username.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
		
		$sections = array();
       foreach ($result as $res) {
            $sections[$res['section']] = $res['academic_modules_id'];
        }
        return $sections;
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
	
	private function getEmployeeUserName($employee_id)
	{
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `emp_id` FROM `employee_details` WHERE `id` = "'.$employee_id.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
		
		$username = NULL;
		
		foreach ($result as $res) {
            $username = $res['emp_id'];
        }
        return $username;
	}
}
