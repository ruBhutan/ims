<?php

namespace GoodsTransaction\Form;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\GoodsReceived;
//use GoodsTransaction\Model\Itemreceivedpurchased;
use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GoodsSuppliedFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {

      //$this->adapter = $dbAdapter;
         // we want to ignore the name passed
        parent::__construct('goodssupplied');
		
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
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Your Item Supplier',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required'
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
                  'required' => 'required'
             ),
         ));

       $this->add(array(
           'name' => 'reference_date',
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
           'name' => 'supplier_order_no',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required'
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