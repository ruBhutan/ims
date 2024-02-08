<?php

namespace GoodsTransaction\Form;

use GoodsTransaction\Model\GoodsReceived;
use GoodsTransaction\Model\ItemReceivedPurchased;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ItemReceivedPurchasedFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(AdapterInterface $dbAdapter)
     {

        $this->adapter = $dbAdapter;  

         // we want to ignore the name passed
        parent::__construct('itemreceivedpurchased');
		
		    $this->setHydrator(new ClassMethods(false));
		    $this->setObject(new ItemReceivedPurchased());
         
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
           'name' => 'item_category_id',
            'type'=> 'Zend\Form\Element\Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Category',
                      'value_options' => $this->getOptionsForSelect(),
             ),
             'attributes' => array(
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item Category',
             ),
         ));

          $this->add(array(
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
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item Sub Category',
             ),
         ));

       $this->add(array(
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
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item Name',
             ),
         ));

       $this->add(array(
           'name' => 'item_quantity_type_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Quantity Type',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item Quantity Type'
             ),
         ));

       $this->add(array(
           'name' => 'item_purchasing_rate',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Purchasing Rate',
             ),
         ));

       $this->add(array(
           'name' => 'item_quantity',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Quantity',
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
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Amount',
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
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item In Stock',
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
                  'class' => 'col-md-3 col-sm-3 form-group',
                  'placeholder' => 'Item Specification',
                  'rows'=> 2,
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
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item Stock Status',
                  'rows'=> 2,
             ),
         ));

       $this->add(array(
           'name' => 'item_status',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Item Status',
                  'rows'=> 2,
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
                  'class' => 'col-md-3 form-group',
                  'placeholder' => 'Remarks',
                  'rows'=> 2,
             ),
         ));
           
         
        /* $this->add(array(
				'name' => 'reset',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Cancel',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-danger',
					),
				
				));*/
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

 
    public function getOptionsForSelect()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id,category_type FROM item_category';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['category_type'];
        }
        return $selectData;
    }   


}