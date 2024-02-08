<?php

namespace GoodsTransaction\Form;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\GoodsReceived;
//use GoodsTransaction\Model\Itemreceivedpurchased;
use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GoodsReceivedDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {

      //$this->adapter = $dbAdapter;
         // we want to ignore the name passed
        parent::__construct('goodsreceiveddetails');
		
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

      /* $this->add(array(
           'name' => 'item_category_type',
            'type'=> 'Zend\Form\Element\Select',
             'options' => array(
                 'class'=>'control-label',
                 //'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Category',
                      'value_options' => array(
                        ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));*/

         /* $this->add(array(
           'name' => 'item_sub_category_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Sub Category Type',
                      'value_options' => array(
                        ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));*/

     /*  $this->add(array(
           'name' => 'item_name_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Name',
                      'value_options' => array(
                        ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'placeholder' => 'Item Name',
             ),
         )); */

       $this->add(array(
           'name' => 'item_purchasing_rate',
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
           'name' => 'item_quantity',
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
           'name' => 'item_amount',
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
         ));*/
           
           
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