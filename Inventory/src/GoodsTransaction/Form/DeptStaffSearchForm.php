<?php

namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class DeptStaffSearchForm extends Form
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
           'name' => 'department',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Division/Section',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				 // 'id' => 'selectProgrammeByTutor',
				  'options' => $this->getDepartmentList(),
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

	private function getDepartmentList()
    {
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`unit_name` AS `unit_name` FROM `department_units` AS `t1`, `departments` AS `t2` WHERE `t1`.`departments_id` = `t2`.`id` AND t2.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['unit_name'];
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
