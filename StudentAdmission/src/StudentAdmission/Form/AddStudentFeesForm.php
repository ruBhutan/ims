<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AddStudentFeesForm extends Form {

  public function __construct() {
    parent::__construct('studentfeedetails');

    $this
          ->setAttribute('method', 'post')
          ->setHydrator(new ClassMethodsHydrator(false))
          ->setInputFilter(new InputFilter())
    ;

    $this->setAttributes(array(
        'class' => 'form-horizontal form-label-left',
    ));

    $this->add(array(
        'type' => 'StudentAdmission\Form\AddStudentFeeDetailsFieldset',
        'options' => array(
            'use_as_base_fieldset' => true,
        ),
    ));

  }

}
