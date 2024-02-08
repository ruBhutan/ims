<?php

namespace StudentStipend\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AddStudentStipendForm extends Form {

    public function __construct() {
        parent::__construct('StudentStipend');

        $this->setAttribute('method', 'post')
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setInputFilter(new InputFilter());

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'type' => 'StudentStipend\Form\AddStudentStipendFieldset',
            'options' => array(
                'use_as_base_fieldset' => true,
            ),
        ));
    }
}
