<?php

namespace StudentStipend\Form;

use StudentStipend\Model\StudentStipend;
use Zend\Validator\Digits;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;

class AddStudentStipendFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {
        parent::__construct('AddStudentStipend');

        $this->setHydrator(new ClassMethods(false));

        $this->setObject(new StudentStipend());

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'Hidden',
                'id' => 'StudentStipendId',
            ),
        ));

        $this->add(array(
            'name' => 'student_id',
            'attributes' => array(
                'type' => 'Hidden',
                'id' => 'StudentStipendStudentId',
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
            'name' => 'stipend',
            'type' => 'Text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
                'id' => 'StudentStipendAmount',
            ),
        ));

        $this->add(array(
            'name' => 'h_r',
            'type' => 'Text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'StudentStipendHR',
            ),
        ));

        $this->add(array(
            'name' => 'ebill',
            'type' => 'Text',
            'options' => array(
                'class' => 'control-label'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'StudentStipendEBill',
            ),
        ));

        $this->add(array(
            'name' => 'net_amount',
            'type' => 'Text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'id' => 'StudentStipendNetAmount',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly'
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
                'class' => 'control-label btn btn-success',
                'value' => 'Add Student Stipend',
                'id' => 'submitbutton'
            ),
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification() {
        return array(
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
            ),
            'stipend' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                ),
                'validators' => array(
                    array('name' => NotEmpty::class)
                )
            ),
            'h_r' => array(
                'required' => false,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                )
            ),
            'ebill' => array(
                'required' => false,
                'filters' => array(
                    array('name' => StripTags::class),
                    array('name' => StringTrim::class),
                )
            ),
            'net_amount' => array(
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
