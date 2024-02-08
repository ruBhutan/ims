<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentFeeDetails;
use Zend\Form\Fieldset;
use zend\InputFilter\InputFilter;
use zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AddStudentFeeDetailsFieldset extends Fieldset implements InputFilterProviderInterface {

  public function __construct() {

    // we want to ignore the name passed
    parent::__construct('addstudentfeedetails');

    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new StudentFeeDetails());

    $this->setAttributes(array(
        'class' => 'form-horizontal form-label-left',
    ));

    $this->add(array(
        'name' => 'id',
        'attributes' => array(
            'type' => 'Hidden',
        ),
    ));
    
    $this->add(array(
        'name' => 'organisation_id',
        'attributes' => array(
            'type' => 'Hidden',
        ),
    ));
    
    $this->add(array(
        'name' => 'student_id',
        'attributes' => array(
            'type' => 'Hidden',
            'id' => 'student_id',
        ),
    ));
    
    $this->add(array(
        'name' => 'student_fee_structure_id',
        'attributes' => array(
            'type' => 'Hidden',
            'id' => 'student_fee_structure_id',
        ),
    ));
    
    $this->add(array(
        'name' => 'student_fee_category_id',
        'type'=> 'Select',
        'options' => array(
          'class'=>'control-label',
          'disable_inarray_validator' => true,
          'empty_option' => 'Select Fee Category'
        ),
        'attributes' => array(
          'id' => 'StudentFeeCategory',
          'class' => 'form-control ',
          'required' => 'required'
        ),
    ));
    
    $this->add(array(
        'name' => 'financial_year',
        'type'=> 'Select',
        'options' => array(
          'empty_option' => 'Select Financial Year',
          'disable_inarray_validator' => true,
          'class'=>'control-label'
        ),
        'attributes' => array(
          'id' => 'FinancialYear',
          'class' => 'form-control ',
          'required' => 'required',
        ),
    ));
    
    $this->add(array(
        'name' => 'semester_id',
        'type'=> 'Select',
        'options' => array(
            'class'=>'control-label',
            'disable_inarray_validator' => true,
            'empty_option' => 'Select Semester'
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
        'name' => 'amount',
        'type'=> 'Text',
        'options' => array(
          'class'=>'control-label',
        ),
        'attributes' => array(
          'id' => 'amount',
          'class' => 'form-control ',
          'required' => 'required',
          'readonly' => 'readonly',
        ),
    ));
    
    $this->add(array(
        'name' => 'status',
        'type'=> 'Select',
        'options' => array(
          'class'=>'control-label',
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
          'class' => 'form-control ',
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
            'class' => 'control-label',
            'value' => 'Add Student Fees',
            'id' => 'submitbutton',
            'disabled' => 'disabled',
            'class' => 'btn btn-success',
        ),
    ));
  }

  /**
   * @return array
   */
  public function getInputFilterSpecification() {
    return array(
        'name' => array(
            'required' => false,
        )
    );
  }

}
