<?php

namespace Accounts\Form;

use Zend\Form\Form;

class StudentFeeReportSearchForm extends Form {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct();

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
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
                'id' => 'selectFeeReportOrganisation',
                'options' => array(),
            ),
        ));

        $this->add(array(
            'name' => 'fee_category',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Please Select Fee Category',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'options' => array(),
                'id' => 'selectFeeReportCategory',
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
                'options' => array(),
            ),
        ));

        $this->add(array(
            'name' => 'payment_status',
            'type' => 'Select',
            'options' => array(
                'class' => 'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Status',
                'value_options' => array(
                    'Pending' => 'Pending',
                    'Completed' => 'Completed',
                    'Canceled' => 'Canceled',
                    'Rejected' => 'Rejected',
                )
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
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
