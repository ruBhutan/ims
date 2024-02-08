<?php

namespace Planning\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
//use Zend\Form\AlumniNewRegistrationForm;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class AwpaObjectivesActivityForm extends Form
{
    protected $serviceLocator;
    
    public function __construct($serviceLocator = null, array $options = [])
     {
         // we want to ignore the name passed
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
        $this->departments_id = $this->getDepartmentId($this->username, $this->organisation_id);
        
         $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left', 
            ));
         
        $this->add(array(
             'type' => 'Planning\Form\AwpaObjectivesActivityFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

        $this->add(array(
           'name' => 'rubobjectives',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Objective',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectRubApaObjectives',
                  'options' => $this->createRubApaObjectives(),
                  'required' => true,
             ),
         ));


         $this->add(array(
            'name' => 'objectives',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Select a Activity',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
                  'id' => 'selectRubApaActivities',
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
     }

     private function createRubApaObjectives()
    { 
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $five_year_plan = $this->getFiveYearPlan();
        $financial_year = $this->getFinancialYear();

        if($this->organisation_id == '1')
        {
            $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`objectives` AS `objectives`, `t2`.`weightage` AS `weightage` FROM `rub_objectives` AS `t1` INNER JOIN `rub_objectives_weightage` AS `t2` ON `t1`.`id` = `t2`.`rub_objectives_id` WHERE t1.five_year_plan_id ="'.$five_year_plan.'" AND t2.organisation_id = "'. $this->organisation_id.'" AND t2.departments_id = "'.$this->departments_id.'" AND t2.financial_year = "'.$financial_year.'"';
        }else{
            $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`objectives` AS `objectives`, `t2`.`weightage` AS `weightage` FROM `rub_objectives` AS `t1` INNER JOIN `rub_objectives_weightage` AS `t2` ON `t1`.`id` = `t2`.`rub_objectives_id` WHERE t1.five_year_plan_id ="'.$five_year_plan.'" AND t2.organisation_id = 0 AND t2.departments_id = 0 AND t2.financial_year = "'.$financial_year.'"';
        }
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) { 
            $selectData[$res['id']] = $res['objectives'].' ('.$res['weightage'].')';
        } //var_dump($selectData); die();
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


    private function getDepartmentId($username, $organisation_id)
    {
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`departments_id` AS `departments_id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'" AND `t1`.`organisation_id` = "'.$this->organisation_id.'"';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $departmentsId = $res['departments_id'];
        }
        return $departmentsId;
    }


    /*
    * Get Five Year Plan
    */
    
    private function getFiveYearPlan()
    {

        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = 'SELECT id FROM five_year_plan WHERE from_date <= "'.date('Y-m-d').'" AND to_date >= "'.date('Y-m-d').'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $five_year_plan = $res['id'];
        }
        return $five_year_plan;
    }


    private function getFinancialYear()
    {
        $financial_year = NULL;
        $date = date('m');

        if($date >=1 && $date <= 6){
                $start_year = date('Y')-1;
                $end_year = date('Y');
                $financial_year = $start_year.'-'.$end_year;
            }else{
                $start_year = date('Y');
                $end_year = date('Y')+1;
                $financial_year = $start_year.'-'.$end_year;
            }

            return $financial_year;
    }

}
