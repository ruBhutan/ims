<?php

namespace Accounts\Form;

use Zend\Form\Form;

class NetPayableForm extends Form {

    public function __construct() {

        parent::__construct();

        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
            'method' => 'GET',
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
            'name' => 'year',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Year',
                'disable_inarray_validator' => true,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectyear',
                'options' => array(),
            ),
        ));

        $this->add(array(
            'name' => 'month',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Month',
                'class' => 'control-label',
                'value_options' => array(
                        '1' => 'January',
                        '2' => 'February',
                        '3' => 'March',
                        '4' => 'April',
                        '5' => 'May',
                        '6' => 'June',
                        '7' => 'July',
                        '8' => 'August',
                        '9' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December',
                    )
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'selectmonth',
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