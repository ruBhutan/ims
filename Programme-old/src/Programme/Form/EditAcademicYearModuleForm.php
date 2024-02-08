<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class EditAcademicYearModuleForm extends Form
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
              'type' => 'Hidden'  
         ));
                
         $this->add(array(
             'name' => 'academic_modules_id',
              'type' => 'Hidden'  
         ));
         
        $this->add(array(
           'name' => 'academic_year',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'disabled' => 'disabled',
             ),
        ));

        $this->add(array(
           'name' => 'year',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'disabled' => 'disabled',
             ),
        ));

        $this->add(array(
           'name' => 'semester',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'disabled' => 'disabled',
             ),
        ));
         
		/*$this->add(array(
           'name' => 'year',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Year',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectYear',
				  'options' => $this->createYearList(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'semester',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Semester',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectSemester',
				  'options' => array(),
             ),
         ));*/

         $this->add(array(
            'name' => 'module_type',
            'type'=> 'select',
              'options' => array(
                  'value_options'=> array(
                         'Compulsory' => 'Compulsory',
                         'Elective' => 'Elective'
                  ),
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'required' => true
              ),
          ));
         $this->add(array(
           'name' => 'module_credit',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
          'required' => true
             ),
         ));
		 
		 $this->add(array(
			'name' => 'academic_session',
			'type' => 'select',
			'options' => array(
				'class' => 'control-label',
				'empty_option' => 'Select Academic Session',
				'value_options' => array(
					'Spring' => 'Spring',
					'Autumn' => 'Autumn'
					),
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Allocate Modules',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'allocate',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Allocate Pre-Defined Modules for the Academic Year',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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
	
	private function createYearList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT MAX(programme_duration) AS max_duration FROM programmes  where organisation_id ="'.$this->organisation_id.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();
		
		foreach ($result as $res) {
            $tmp_number = $res['max_duration'];
			preg_match_all('!\d+!', $tmp_number, $matches);
			$max_years = implode(' ', $matches[0]);
        }
		
		for($i=1; $i<=$max_years; $i++){
			$selectData[$i] = $i ." Year ";
		}
        return $selectData;
    }

}