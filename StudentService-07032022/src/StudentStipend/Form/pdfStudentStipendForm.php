<?php

namespace StudentStipend\Form;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilterProviderInterface;

class pdfStudentStipendForm extends Form implements InputFilterProviderInterface {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct("pdfStudentStipendForm");

        $this->setAttribute('method', 'post')
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setInputFilter(new InputFilter());

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'name' => 'organisation_id',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Select Organisation',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'id' => 'OrganisationId',
                'class' => 'form-control',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'year',
            'type' => 'Select',
            'options' => array(
                'class' => 'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Year'
            ),
            'attributes' => array(
                'id' => 'StudentStipendYear',
                'class' => 'form-control',
                'required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'month',
            'type' => 'Select',
            'options' => array(
                'class' => 'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Month'
            ),
            'attributes' => array(
                'id' => 'StudentStipendMonth',
                'class' => 'form-control',
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

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Generate PDF',
                'class' => 'btn btn-success'
            ),
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification() {
        return array(
            'organisation_id' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class)
                )
            ),
            'year' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class)
                )
            ),
            'month' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class)
                )
            )
        );
    }

}
