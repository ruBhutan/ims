<?php

namespace GoodsRequisition\Form;

use GoodsRequisition\Model\GoodsTransfer;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GoodsTransferFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('goodstransfer');
    
    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new GoodsTransfer());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'department_head_from',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
     
         $this->add(array(
           'name' => 'department_head_to',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
          $this->add(array(
           'name' => 'transfer_date',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
          $this->add(array(
           'name' => 'item_name_id',
            'type'=> 'Zend\Form\Element\Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Name',
                 'value_options' => array(
                    '0' => 'Select',
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
          ));
              
          $this->add(array(
           'name' => 'item_quantity',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
         $this->add(array(
           'name' => 'rate',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
          'rows' => '3',
             ),
         )); 

    $this->add(array(
           'name' => 'amount',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'remarks',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
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
         return array(
             'name' => array(
                 'required' => false,
             ),
         );
     }
}
