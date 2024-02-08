<?php

namespace GoodsTransaction\Form;

//use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\GoodsTransfer;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UpdateDeptGoodsTransferFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
     {
         // we want to ignore the name passed
      parent::__construct('updatedeptgoodstransfer');
    
      $this->setHydrator(new ClassMethods(false));
      $this->setObject(new GoodsTransfer());
         
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
           'name' => 'department_to_id',
             'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

        $this->add(array(
           'name' => 'employee_details_to_id',
             'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

        $this->add(array(
           'name' => 'department_from_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
           'name' => 'employee_details_from_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));         

         $this->add(array(
           'name' => 'department_goods_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

         $this->add(array(
           'name' => 'goods_transfer_date',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

        $this->add(array(
           'name' => 'transfer_update_date',
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
           'name' => 'goods_transfer_status',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'empty_option' => 'Select Status',
                      'value_options' => array(
                        'Pending' => 'Pending',
                        'Approved' => 'Approve',
                        'Rejected' => 'Reject',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));

         $this->add(array(
           'name' => 'transfer_quantity',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));

         $this->add(array(
           'name' => 'transfer_applied_remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => '3',
                  'readonly' => 'readonly',
             ),
         ));


         $this->add(array(
           'name' => 'transfer_approved_remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => '3',
             ),
         ));

        $this->add(array(
           'name' => 'unit_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
           'name' => 'item_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
           'name' => 'item_specification',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
                  'rows' => '3',
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