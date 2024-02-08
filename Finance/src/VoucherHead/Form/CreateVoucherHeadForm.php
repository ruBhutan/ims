<?php

namespace VoucherHead\Form;

use Zend\Form\Form;

class CreateVoucherHeadForm extends Form
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
           'name' => 'tutor',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '1' => 'Select Tutor',
                     '2' => 'Dorjee Wangmo'
                    // '3' => 'Male'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>'1'//set selected to '1'
             ),
         ));
          
            $this->add(array(
           'name' => 'voucher_head',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
            
            $this->add(array(
           'name' => 'voucher_master',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    '1' => '--Select Voucher Master--',
                     '2' => 'Cash Receipt',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
            
             $this->add(array(
           'name' => 'ledger_account',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    '1' => '--Select Ledger Account--',
                     '2' => 'Stationary',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
         
            $this->add(array(
           'name' => 'number',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    '1' => 'Select date',
                     '2' => '2003',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
  
  
            
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Create',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
					),
				
				));
     }
}