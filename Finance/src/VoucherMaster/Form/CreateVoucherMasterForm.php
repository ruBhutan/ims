<?php

namespace VoucherMaster\Form;

use Zend\Form\Form;

class CreateVoucherMasterForm extends Form
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
           'name' => 'voucher_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 
             ),
         ));

         $this->add(array(
             'name' => 'voucher_status',
               'type'=>'Select',
             'options' => array(
               'class' => 'control-label', 
                 'value_options' => array(
                     '1' => '--Please Select--',
                     '2' => 'Credit',
                     '3' => 'Debit',
                 ),
             ),
             'attributes' => array(
                 'class' => 'form-control',
               
                 'rows'=>'3',
             ),
         ));
         
    
         $this->add(array(
				'name' => 'save',
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



