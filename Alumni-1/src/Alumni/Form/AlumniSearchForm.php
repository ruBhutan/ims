<?php

namespace Alumni\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AlumniSearchForm extends Form
{
    public function __construct()
    {
         // we want to ignore the name passed
        parent::__construct();
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
            'name' => 'alumni_programme',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Please Select Alumni Programme',
                 'disable_inarray_validator' => true,
                'class' => 'control-label',
                ),
            'attributes' =>array(
                'class' => 'form-control',
                'required' => 'required',
                ),
        ));

        $this->add(array(
            'name' => 'alumni_batch',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Please Select Batch',
                 'disable_inarray_validator' => true,
                'class' => 'control-label',
                ),
            'attributes' =>array(
                'class' => 'form-control',
                'required' => 'required',
                ),
        ));

        $this->add(array(
            'name' => 'alumni_name',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
                ),
            'attributes' =>array(
                'class' => 'form-control',
                'placeholder' => 'Alumni Name',
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