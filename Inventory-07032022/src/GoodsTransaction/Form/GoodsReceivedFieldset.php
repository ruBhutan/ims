<?php

namespace GoodsTransaction\Form;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\GoodsReceived;
use GoodsTransaction\Model\ItemReceivedPurchased;
use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GoodsReceivedFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('goodsreceived');
    
    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new GoodsReceived());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));

         $this->add(array(
           'name' => 'supplier_details_id',
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
           'name' => 'reference_no',
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
           'name' => 'reference_date',
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
           'name' => 'supplier_order_no',
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
           'name' => 'receipt_voucher_no',
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
           'name' => 'supplier_name',
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
           'name' => 'organisation_id',
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
   
   /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'name' => array(
                 'required' => false,
             ),
         );
     }
}