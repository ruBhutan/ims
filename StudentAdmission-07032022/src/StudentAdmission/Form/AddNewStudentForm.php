<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
//use Zend\Form\AlumniNewRegistrationForm;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class AddNewStudentForm extends Form
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

         $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left', 
            ));

         $this->add(array(
             'type' => 'StudentAdmission\Form\AddNewStudentFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
           'name' => 'programme_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Programme',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  //'id' => 'selectNewStudentProgramme',
                  'options' => $this->createProgrammesId(),
                  'required' => 'required',
             ),
         ));


         $this->add(array(
            'name' => 'country_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Country',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
                  'id' => 'selectStudentCountry',
                  'options' => $this->createCountry(),
             ),
         ));

         $this->add(array(
            'name' => 'dzongkhag',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Dzongkhag',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
                  'id' => 'selectStudentDzongkhag',
                  'options' => array(),
             ),
         ));
        
        $this->add(array(
            'name' => 'gewog',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Gewog',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
                  'id' => 'selectStudentGewog',
                  'options' => array(),
             ),
         ));

        $this->add(array(
            'name' => 'village',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Village',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
                  'id' => 'selectStudentVillage',
                  'options' => array(),
             ),
         ));

          /*$this->add(array(
                'name' => 'semester_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Semester',
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'selectProgrammeSemester',
                    'options' => array(),
                    'required' => 'required'
                ),
            ));*/

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

         private function createProgrammesId()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`programme_name` AS `programme_name` FROM `programmes` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['programme_name'];
        }
        return $selectData;
    }

    private function getProgrammeYear()
    {
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`year` AS `year` FROM `programme_year` AS `t1`';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['year'];
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

    private function createCountry()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, country FROM country';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['country'];
        }
        return $selectData;
    }
}