<?php

namespace GoodsRequisition\Form;

use GoodsRequisition\Model\GoodsRequisition;
use GoodsRequisition\Model\GoodsRequisitionApproval;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GoodsRequisitionFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('goodsrequisition');
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new GoodsRequisition());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
          $this->add(array(
           'name' => 'requisition_item_quantity',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
  
          $this->add(array(
           'name' => 'item_specification',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                   'rows' => '4',
             ),

         ));
              

          $this->add(array(
           'name' => 'item_quantity_stock',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
           
         $this->add(array(
           'name' => 'purpose',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'rows' => '3',
             ),
         )); 
         
         $this->add(array(
           'name' => 'requisition_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
    
    
           $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'readonly' => 'readonly',
             ),
         ));


           $this->add(array(
           'name' => 'requisition_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

           $this->add(array(
           'name' => 'requisition_remarks',
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
                        'value' => 'Add',
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