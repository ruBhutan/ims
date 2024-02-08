<?php

namespace Hostel\Form;

use Hostel\Model\Hostel;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class HostelFieldset extends Fieldset implements InputFilterProviderInterface
{

  public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('hostel');
    
    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new Hostel());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'hostel_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));

         $this->add(array(
			'name' => 'hostel_type',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Hostel Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
		));
     
     $this->add(array(
           'name' => 'hostel_category',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
         'empty_option' => 'Select Category',
         'value_options' => array(
            'Boys' => 'Boys',
            'Girls' => 'Girls'
          ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
     
     $this->add(array(
           'name' => 'hostel_room_no',
            'type'=> 'Number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));

         $this->add(array(
            'name' => 'additional_hostel_room_no',
             'type'=> 'Number',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control',
                   'required' => false
              ),
          ));
     
     $this->add(array(
           'name' => 'hostel_floor_no',
            'type'=> 'Number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
     
     $this->add(array(
           'name' => 'room_capacity',
            'type'=> 'Number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
     
     $this->add(array(
           'name' => 'provost_name',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select Provost/ Resident Coordinator',
                'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
         
     $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                    'rows' => 3
             ),
         ));
     
     $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
         
     $this->add(array(
      'name' => 'submit',
       'type' => 'Submit',
        'attributes' => array(
          'value' => 'Submit',
          'id' => 'submitbutton',
                        'class' => 'btn btn-success',
        ),
      ));
    
     }
   
   /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return [
             [
                'name' => 'additional_hostel_room_no',
                'required' => false,
             ]
         ];
     }
}