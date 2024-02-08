<?php

namespace StudentAdmission\Form;

use Zend\Form\Form;

class AddBulkFeeForm extends Form {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct("addbulkfeesform");

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
              'class' => 'form-control',
              'required' => 'required',
           ),
        ));

        $this->add(array(
           'name' => 'structure_id',
           'type' => 'Select',
           'options' => array(
              'empty_option' => 'Fee Structure (Category - Amount (Year))',
              'class' => 'control-label',
              'disable_inarray_validator' => true
           ),
           'attributes' => array(
              'class' => 'form-control ',
              'required' => 'required'
           ),
        ));
        
        $this->add(array(
            'name' => 'due_date',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
            ),
            'attributes' => array(
              'class' => 'form-control ',
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
              'value' => 'Generate Student Fees',
              'class' => 'btn btn-success'
           ),
        ));
    }

}
