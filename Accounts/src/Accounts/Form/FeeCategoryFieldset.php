<?php

namespace Accounts\Form;

use Accounts\Model\StudentFeeCategory;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\Digits;
use Zend\Validator\NotEmpty;

class FeeCategoryFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        // we want to ignore the name passed
        parent::__construct('StudentFeeCategory');

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new StudentFeeCategory());

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));

        $this->add(array(
            'name' => 'organisation_id',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Please Select Organisation',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectFeeCategoryOrganisation',
                'options' => array(),
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'fee_category',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'remarks',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
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
                'value' => 'Add Student Fee Category',
                'id' => 'submitButton',
                'class' => 'btn btn-success',
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
                'validators' => array(
                    array('name' => NotEmpty::class),
                    array('name' => Digits::class)
                )
            ),
            'fee_category' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class)
                )
            ),
            'remarks' => array(
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
