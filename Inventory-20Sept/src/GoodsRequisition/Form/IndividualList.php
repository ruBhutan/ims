<?php
namespace IndividualList\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class SubmitGoodsRequisitionForm extends Form
{
    public function __construct($name = null)
    {
	   parent::__construct('submitgoodsrequisition');

	   $this->setAttribute('method', 'post');
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
            'name' => 'emp_id',
            'attributes' => array(
                'type'  => 'text',
              ),
            ));
	   
	   $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
