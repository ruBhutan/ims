<?php

namespace Accounts\Form;

use Zend\Form\Form;

class EmployeePayrollSearchForm extends Form {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct();

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
            'method' => 'GET',
        ));

        $this->add(array(
            'name' => 'employee_name',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Employee Name',
            ),
        ));

        $this->add(array(
            'name' => 'employee_id',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Employee ID',
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
                'id' => 'selectEmployeeOrganisation',
                'options' => array(),
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
                'value' => 'Search',
                'id' => 'submitbutton',
                'class' => 'btn btn-success'
            ),
        ));
    }
}