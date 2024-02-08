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
    
    public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('awpaobjectivesactivity');

         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
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
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                        'timeout' => 600
                )
             )
         ));
     }

}
