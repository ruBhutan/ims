<?php

namespace GoodsTransaction\Form;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\GoodsReceived;
//use GoodsTransaction\Model\Itemreceivedpurchased;
use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GoodsReceivedPurchasedFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {

      //$this->adapter = $dbAdapter;
         // we want to ignore the name passed
        parent::__construct('goodsreceivedpurchased');
		
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
           'name' => 'item_received_type',
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
           'name' => 'item_entry_date',
            'type'=> 'Date',
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
           'name' => 'item_in_stock',
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
           'name' => 'item_received_by',
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
           'name' => 'item_received_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                  'id' => 'single_cal2'
             ),
         ));

       $this->add(array(
           'name' => 'item_verified_by',
            'type'=> 'Select',
             'options' => array(
                  'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => '--Select--',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required'
             ),
         ));

       $this->add(array(
           'name' => 'item_received_purchased_id',
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

      /* $this->add(array(
           'name' => 'item_purchasing_rate',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required'
             ),
         ));
        */
       $this->add(array(
           'name' => 'item_purchasing_rate',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
                  'min' => 0.0,
                  'step' => 0.01
             ),
         ));

       $this->add(array(
           'name' => 'item_quantity',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
		  'required' => 'required',
		  'min' => 0.0,
		  'step' => 0.01
             ),
         ));

       $this->add(array(
           'name' => 'item_amount',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required'
             ),
         ));

       $this->add(array(
           'name' => 'item_status',
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
           'name' => 'item_specification',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows'=> 4,
             ),
         ));

       $this->add(array(
           'name' => 'item_stock_status',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows'=> 4,
             ),
         ));

       $this->add(array(
           'name' => 'remarks',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows'=> 4,
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
                  'class' => 'form-control',
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
                  'class' => 'form-control',
             ),
         ));

       /* $this->add(array(
         'name' => 'goodsreceiveddetails',
         'type' => 'Zend\Form\Element\Collection',
         'options' => array(
            'count'=> 2,
          'should_create_template' => true,
          'allow_add' => true,
          'target_element' => array(
            'type' => 'GoodsTransaction\Form\GoodsReceivedDetailsFieldset',
          ),
         ),
     ));*/
           
           
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
