<?php

namespace InventoryReports\Form;

use Zend\Form\Form;

class InventoryReportsForm extends Form
{
  public function __construct()
  {
     // we want to ignore the name passed
    parent::__construct();
    
    $this->setAttributes(array(
      'class' => 'form-horizontal form-label-left',
    ));
    
    $this->add(array(
      'name' => 'report_name',
      'type'=> 'select',
          'options' => array(
              'empty_option' => 'Select a Report',
                  'disable_inarray_validator' => true,
                  'class'=>'control-label',
          ),
          'attributes' => array(
               'class' => 'form-control ',
          ),
      ));
                
      $this->add(array(
        'name' => 'organisation',
         'type'=> 'select',
          'options' => array(
              'empty_option' => 'Select an Agency/College',
              'disable_inarray_validator' => true,
              'class'=>'control-label',
          ),
          'attributes' => array(
               'class' => 'form-control ',
          ),
      ));

      $this->add(array(
            'name' => 'position',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select the Position',
                'disable_inarray_validator' => true,
                'class'=>'control-label',
                'value_options'=> array(
                    '1' => 'Vice Chancellor',
                    '2' => 'Registrar',
                    '3' => 'President',
                    '4' => 'Director for Academic Affairs',
                    '5' => 'Director for Research and External Relations',
                    '6' => 'Director for Planning & Resources'
                ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
      
      $this->add(array(
        'name' => 'financial_year',
         'type'=> 'select',
          'options' => array(
              'empty_option' => 'Select a Financial Plan',
              'disable_inarray_validator' => true,
              'class'=>'control-label',
          ),
          'attributes' => array(
               'class' => 'form-control ',
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