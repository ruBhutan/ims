<?php

namespace Administration\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class UserWorkFlowForm extends Form
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

         //the following are so that we can get the organisation id
       $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
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
           'name' => 'organisation',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
           'name' => 'role',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Role',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
                  'options' => $this->createRole(),
             ),
         ));

         $this->add(array(
           'name' => 'user_department',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Department',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'id' => 'selectUserRoleDepartment',
                  'options' => $this->createRoleDepartment(),
             ),
         ));
         
        $this->add(array(
           'name' => 'role_department',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Unit',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'id' => 'selectUserRoleUnit',
                  'options' => array(),
             ),
         ));
         
         $this->add(array(
           'name' => 'type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Type',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
         
         $this->add(array(
           'name' => 'auth',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Authorising Role',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'options' => $this->createAuthRole(),
             ),
         ));
         
         $this->add(array(
           'name' => 'department',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Authoriser Department',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'options' => $this->createAuthDepartment(),
             ),
         ));
         
         
         $this->add(array(
           'name' => 'details',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows' => 3
             ),
         ));

         $this->add(array(
           'name' => 'auth_department_name',
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
                    'value' => 'Add User Workflow',
                    'id' => 'submitbutton',
                        'class' => 'btn btn-success',
                ),
          ));
     }

     private function createRole()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`rolename` AS `rolename` FROM `user_role` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData['ALL'] = 'ALL';
            $selectData[$res['rolename']] = $res['rolename'];
        }
        return $selectData;
    }

    private function createRoleDepartment()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`department_name` AS `department_name` FROM `departments` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData['ALL'] = 'ALL';
            $selectData[$res['id']] = $res['department_name'];
        }
        return $selectData;
    }


    private function createAuthRole()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`rolename` AS `rolename` FROM `user_role` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['rolename']] = $res['rolename'];
        }
        return $selectData;
    }


    private function createAuthDepartment()
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