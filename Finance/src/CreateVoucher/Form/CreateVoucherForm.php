<?php

namespace CreateVoucher\Form;

use Zend\Form\Form;

class CreateVoucherForm extends Form
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
           'name' => 'voucher_number',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>'--enter voucher no. here--'
             ),
         ));
          
            $this->add(array(
           'name' => 'transaction_date',
            'type'=> 'Date',
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
           'name' => 'voucher_head',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    '1' => '--Select Voucher Head--',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
            
             $this->add(array(
           'name' => 'from_ledger_account',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    '1' => '--Select Ledger Account From--',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
             
             $this->add(array(
           'name' => 'to_ledger_account',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                    '1' => '--Select Ledger Account To--',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                
             ),
         ));
         
            $this->add(array(
           'name' => 'amount',
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
                'value' =>'--enter amount here--'
             ),
         ));
  
            $this->add(array(
           'name' => 'description',
            'type'=> 'TextArea',
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