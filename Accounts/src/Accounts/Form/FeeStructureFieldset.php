<?php

namespace Accounts\Form;

use Accounts\Model\StudentFeeStructure;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\Digits;
use Zend\Validator\GreaterThan;
use Zend\Validator\NotEmpty;

class FeeStructureFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        // we want to ignore the name passed
        parent::__construct('FeeStructure');

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new StudentFeeStructure());

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));

        $this->add(array(
            'name' => 'student_fee_category_id',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Please Select Category',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectFeeStructureCategory',
                'options' => array(),
                'required' => 'required',
            ),
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
                'id' => 'selectFeeStructureOrganisation',
                'options' => array(),
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'programmes_id',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Please Select Programme',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectFeeStructureProgramme',
                'options' => array(),
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'financial_year',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Please Select Financial Year',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectFeeStructureFinancialYear',
                'options' => array(),
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'total_fee',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
                'id' => 'feeStructureTotalFee'
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
                'value' => 'Add Student Fee Structure',
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
            'student_fee_category_id' => array(
                'required' => true,
                'validators' => array(
                    array('name' => NotEmpty::class),
                    array('name' => Digits::class)
                )
            ),
            'organisation_id' => array(
                'required' => true,
                'validators' => array(
                    array('name' => NotEmpty::class),
                    array('name' => Digits::class)
                )
            ),
            'programmes_id' => array(
                'required' => true,
                'validators' => array(
                    array('name' => NotEmpty::class),
                    array('name' => Digits::class)
                )
            ),
            'financial_year' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class)
                )
            ),
            'total_fee' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class),
                    array(
                        'name' => GreaterThan::class,
                        'options' => ['min' => 0, 'inclusive' => true]
                    )
                )
            )

        );
    }

}
