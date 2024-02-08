<?php

namespace FeeAllocation\Form;

use Zend\Form\Form;

class AddFeeAllocationForm extends Form
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct();
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
          $this->add(array(
           'name' => 'fees_category',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                          '1' => '--Select--',
                          '2' => 'Sports',
                          '3' => 'College Fees',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           $this->add(array(
           'name' => 'fee_sub_category',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                            '1' => '--Select--',
                          '2' => 'badminton',
                          '3' => 'Mess',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
               $this->add(array(
           'name' => 'fee_amount',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
            

         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Save',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
					),
				
				));
     }
}