<?php
namespace UniversityResearch\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AurgActionPlanForm extends Form
 {
     public function __construct()
     {
        parent::__construct('aurgactionplan');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'type' => 'UniversityResearch\Form\AurgActionPlanFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

                $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                        'timeout' => 1200
                )
             )
         ));
     }
 }