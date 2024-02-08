<?php
namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class GoodsTransferApprovalForm extends Form
 {
     public function __construct()
     {
        parent::__construct('transfergoods');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        $this->add(array(
             'type' => 'GoodsTransaction\Form\GoodsTransferApprovalFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));
		 
       /* $this->add(array(
            // 'type' => 'Zend\Form\Element\Csrf',
             'name' => 'reset',
             'attributes' => array(
                'type' => 'submit', 
                'value' => 'Cancel',
                ),
         ));*/

         $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                 'type' => 'submit',
                 'value' => 'Send',
             ),
         ));
     }
 }