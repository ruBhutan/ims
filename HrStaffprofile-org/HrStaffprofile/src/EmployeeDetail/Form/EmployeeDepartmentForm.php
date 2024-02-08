<?php

namespace EmployeeDetail\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class EmployeeDepartmentForm extends Form
{
    protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
     {
		 parent::__construct('ajax', $options);
		
		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        $this->ajax = $options;
		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

         /*
         * To retrieve the user name from the session
        */
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
             'name' => 'organisation_id',
              'type' => 'Hidden'  
         ));

        $this->add(array(
           'name' => 'order_date',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
          'id' => 'single_cal3'
             ),
         ));

         $this->add(array(
           'name' => 'new_departments_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Department',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
                  'id' => 'selectDepartments',
                  'options' => $this->createDepartmentList(),
             ),
         ));

         $this->add(array(
           'name' => 'new_departments_units_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Department Unit',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
                  'id' => 'selectUnits',
                  'options' => array(),
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
				  'id' => 'selectDepartmentOccupationGroup',
				  'options' => $this->createOccupationalGroup(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'recommended_position_category',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectDepartmentCategory',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'recommended_position_title',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectDepartmentPositionTitle',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'recommended_position_level',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectDepartmentPositionLevel',
				  'options' => array(),
             ),
         )); 

		 
		 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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

    private function createDepartmentList()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`department_name` AS `department_name` FROM `departments` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['department_name'];
        }
        return $selectData;
    }
	
	private function createOccupationalGroup()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, major_occupational_group FROM major_occupational_group';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['major_occupational_group'];
        }
        return $selectData;
    }
}