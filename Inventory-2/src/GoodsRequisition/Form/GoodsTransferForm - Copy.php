<?php
namespace GoodsRequisition\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class GoodsTransferForm extends Form
 {
     public function __construct()
     {
        parent::__construct('goodstransfer');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'type' => 'GoodsRequisition\Form\GoodsTransferFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));
	
         $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                 'type' => 'submit',
                 'value' => 'Send',
             ),
         ));
     }
 }