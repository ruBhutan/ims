<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
//use Zend\Form\AlumniNewRegistrationForm;

class UpdateStudentParentDetailsForm extends Form
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
             'type' => 'StudentAdmission\Form\UpdateStudentParentDetailsFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));


        // Father
        $this->add(array(
            'name' => 'father_dzongkhag',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Dzongkhag',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectFatherDzongkhag',
                  'options' => $this->createFatherDzongkhag(),
             ),
         ));
        
        $this->add(array(
            'name' => 'father_gewog',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Gewog',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectFatherGewog',
                  'options' => array(),
             ),
         ));

        $this->add(array(
            'name' => 'father_village',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Village',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectFatherVillage',
                  'options' => array(),
             ),
         ));


        //Mother
        $this->add(array(
            'name' => 'mother_dzongkhag',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Dzongkhag',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectMotherDzongkhag',
                  'options' => $this->createMotherDzongkhag(),
             ),
         ));
        
        $this->add(array(
            'name' => 'mother_gewog',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Gewog',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectMotherGewog',
                  'options' => array(),
             ),
         ));

        $this->add(array(
            'name' => 'mother_village',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Village',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectMotherVillage',
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

    private function createFatherDzongkhag()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, dzongkhag_name FROM dzongkhag';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['dzongkhag_name'];
        }
        return $selectData;
    }

    private function createMotherDzongkhag()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, dzongkhag_name FROM dzongkhag';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['dzongkhag_name'];
        }
        return $selectData;
    }
}