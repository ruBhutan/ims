<?php

namespace Accounts\Form;

use Zend\Form\Form;

class SalaryReportForm extends Form {

    public function __construct() {

        parent::__construct();

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
            'method' => 'GET',
        ));

        $this->add(array(
            'name' => 'financial_year',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Financial Year',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectfinancialyear',
                'options' => array(),
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Payment Status',
                'class' => 'control-label',
                'value_options' => array(
                        '0' => 'Pending',
                        '1' => 'Paid',
                        '2' => 'Advance',
                    )
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectstatus',
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
