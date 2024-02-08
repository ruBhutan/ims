<?php

namespace EmpWorkForceProposal\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EmpWorkForceProposalForm extends Form
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('add_empworkforceproposal');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

         $this->add(array(
             'type' => 'EmpWorkForceProposal\Form\EmpWorkForceProposalFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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