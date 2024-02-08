<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class DpdAssessmentForm extends Form
{
	protected $username;
	protected $organisation_id;
    protected $serviceLocator;
	
	public function __construct($serviceLocator = null, array $options = [])
     {
        /*parent::__construct('budgetproposal');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 */
		 parent::__construct('ajax', $options);
		 
		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        $this->ajax = $options;
		
		$authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
		$this->organisation_id = $this->getOrganisationId($this->username);
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
       $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
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
				  'id' => 'selectProgrammeNameForAssessment',
				  'required' => true,
				  'options' => $this->createProgrammeList(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'academic_modules_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Academic Module',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectModuleNameForAssessment'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'assessment_component_types_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Assessment Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'assessment',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'assessment_marks',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
                  'min' => 0.0,
                  'step' => 0.01
             ),
         ));
		 
		 $this->add(array(
           'name' => 'assessment_weightage',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'min' => 0.0,
				  'step' => 0.01
             ),
         ));
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
	     'options' => array(
		'csrf_options' => array(
			'timeout' => 1800
             	)
	     )
         ));


         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Mark Allocation',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
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
	 
	private function createAcademicModule()
    {
        //need to get which part of the year so that we do not mix the enrollment years
		//$semester = $this->getSemester();
		//$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		
		$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`module_title` AS `module_title`, `t1`.`module_code` AS `module_code`,`t2`.`module_coordinator` AS `module_coordinator` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_coordinators` AS `t2` ON `t1`.`academic_modules_id` = `t2`.`id` WHERE t2.module_coordinator = "'.$this->username.'" AND t1.academic_session = "'.$semester.'" AND t1.academic_year = "'.$academic_year.'"';
		
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['module_title']." (".$res['module_code'].")";
        }
		
        return $selectData;
    }
	
	private function getOrganisationId($username)
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id ="'.$this->username.'"';
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
		$sql = 'SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE from_date <= "'.date('Y-m-d').'" AND to_date >= "'.date('Y-m-d').'"  AND t2.organisation_id = "'.$this->organisation_id.'"';
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
	
	private function getAcademicYear()
	{
		$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		
		return $academic_year;
	}
}
