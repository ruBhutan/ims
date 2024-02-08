<?php

namespace Accounts\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class FeeCategory extends Form {

    protected $serviceLocator;

    public function __construct() {
        parent::__construct('StudentFeeCategory');

        $this
            ->setAttribute('method', 'post')
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setInputFilter(new InputFilter());

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'type' => 'Accounts\Form\FeeCategoryFieldset',
            'options' => array(
                'use_as_base_fieldset' => true,
            ),
        ));
    }
}
