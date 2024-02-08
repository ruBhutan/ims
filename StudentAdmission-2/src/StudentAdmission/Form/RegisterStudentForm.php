<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
//use Zend\Form\AlumniNewRegistrationForm;

class RegisterStudentForm extends Form
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

         $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left', 
            ));

         $this->add(array(
             'type' => 'StudentAdmission\Form\RegisterStudentFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select organisation',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectStdRegisterOrganisation',
                  'options' => $this->createStdRegisterOrganisation(),
                  'required' => 'required'
             ),
         ));



        $this->add(array(
           'name' => 'programme_id',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select Programme',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectselectStdRegisterProgramme',
                  'options' => array(),
                  'required' => 'required'
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

    private function createStdRegisterOrganisation()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, organisation_name FROM organisation';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
    }
}