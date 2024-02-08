<?php

namespace Accounts\Form;

use Zend\Form\Form;

class EmployeeEarningDeductionForm extends Form {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct();

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'name' => 'payhead',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Select Payhead',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectPayhead',
                'required' => 'required',
                'options' => array(),
            ),
        ));

        $this->add(array(
            'name' => 'amount',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'earningAndDeductionAmount',
                'placeholder' => 'Amount',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'ref_no',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'ref_no',
                'placeholder' => 'Reference No.',
            ),
        ));

        $this->add(array(
            'name' => 'remark',
            'type' => 'textarea',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'remark',
                'placeholder' => 'Remark',
            ),
        ));

        $this->add(array(
            'name' => 'is_dlwp',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Depends on LWP :',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
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
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-success'
            ),
        ));
    }
}
