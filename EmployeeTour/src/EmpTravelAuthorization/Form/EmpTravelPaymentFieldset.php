<?php

namespace EmpTravelAuthorization\Form;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;
use Zend\Validator\Digits;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;

class EmpTravelPaymentFieldset extends Fieldset implements InputFilterProviderInterface {

  public function __construct() {
    parent::__construct('emptravelPayment');

    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new EmpTravelAuthorization());

    $this->setAttributes(array(
        'class' => 'form-horizontal form-label-left',
    ));

    $this->add(array(
        'name' => 'amount',
        'type' => 'number',
        'options' => array(
          'class' => 'control-label',
        ),
        'attributes' => array(
          'id' => 'amount',
          'min' => '0',
          'step' => '0.01',
          'class' => 'form-control ',
          'required' => 'required',
        )
    ));
    
    $this->add(array(
        'name' => 'payment_type',
        'type'=> 'Select',
        'options' => array(
          'class'=>'control-label',
          'disable_inarray_validator' => true,
          'empty_option' => 'Select Payment Type'
        ),
        'attributes' => array(
          'id' => 'PaymentTypes',
          'class' => 'form-control ',
          'required' => 'required'
        )
    ));
    
    $this->add(array(
        'name' => 'cheque_no',
        'type'=> 'number',
        'options' => array(
          'class'=>'control-label',
        ),
        'attributes' => array(
          'id' => 'cheque_no',
          'min' => '0',
          'class' => 'form-control ',
        )
    ));
    
    $this->add(array(
        'name' => 'dd_no',
        'type'=> 'number',
        'options' => array(
          'class'=>'control-label',
        ),
        'attributes' => array(
          'id' => 'dd_no',
          'min' => '0',
          'class' => 'form-control ',
        )
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
            'value' => 'Make Payment',
            'id' => 'submitbutton',
            'class' => 'btn btn-success',
        ),
    ));
  }

  /**
   * @return array
   */
  public function getInputFilterSpecification() {
    return array(
      'amount' => array(
        'required' => true,
        'filters' => array(
          array('name' => StripTags::class),
          array('name' => StringTrim::class),
        ),
        'validators' => array(
          array('name' => NotEmpty::class),
        )
      ),
      'payment_type' => array(
        'required' => true,
        'validators' => array(
          array('name' => Digits::class),
          array('name' => NotEmpty::class)
        )
      ),
      'cheque_no' => array(
        'required' => false,
        'filters' => array(
          array('name' => StripTags::class),
          array('name' => StringTrim::class),
        ),
        'validators' => array(
          array('name' => Digits::class)
        )
      ),
      'dd_no' => array(
        'required' => false,
        'filters' => array(
          array('name' => StripTags::class),
          array('name' => StringTrim::class),
        ),
        'validators' => array(
          array('name' => Digits::class)
        )
      ),
      'status' => array(
        'required' => false
      )
    );
  }

}
