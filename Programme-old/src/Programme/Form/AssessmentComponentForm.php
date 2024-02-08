<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class AssessmentComponentForm extends Form
{
	protected $username;
	protected $organisation_id;
    protected $serviceLocator;
	
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
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
		 $this->add(array(
           'name' => 'assessment',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Assessment Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'weightage',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
           
         $this->add(array(
           'name' => 'remarks',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3
             ),
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
                 'empty_option' => 'Please Select a Module',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectModuleNameForAssessment',
				  'required' => true,
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Assessment Component',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
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
}
