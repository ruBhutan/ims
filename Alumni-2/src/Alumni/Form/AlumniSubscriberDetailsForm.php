<?php

namespace Alumni\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
//use Zend\Form\AlumniNewRegistrationForm;

class AlumniSubscriberDetailsForm extends Form
{
    protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
     {
         // we want to ignore the name passed
        parent::__construct('ajax', $options);

        $this->serviceLocator = $serviceLocator;
        $this->ajax = $serviceLocator;
        $this->ajax = $options;
         
        $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

        $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

        $this->add(array(
             'type' => 'Alumni\Form\AlumniSubscriberDetailsFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

        $this->add(array(
                'name' => 'subscription_type',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'empty_option' => 'Select Subscription Type',
                    'value_options' => array(
                        'Yearly' => 'Yearly',
                        'Lifetime' => 'Lifetime',
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'selectAlumniSubscriptionType',
                    'required' => 'required',
                ),
            ));

        $this->add(array(
           'name' => 'subscription_charge',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                  'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectAlumniSubscriptionCharge',
             ),
         ));


         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));
     }
}