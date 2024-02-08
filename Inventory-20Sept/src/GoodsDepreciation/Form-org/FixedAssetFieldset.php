<?php

namespace GoodsDepreciation\Form;

use GoodsDepreciation\Model\GoodsDepreciation;
use GoodsDepreciation\Model\FixedAsset;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class FixedAssetFieldset extends Fieldset implements InputFilterProviderInterface
{
        public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('fixed_asset');

                $this->setHydrator(new ClassMethods(false));
                $this->setObject(new FixedAsset());

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
           'name' => 'item_name_id',
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
           'name' => 'item_quantity_type',
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
           'name' => 'depreciation_rate',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'require' => 'required',
             ),
         ));

         $this->add(array(
           'name' => 'depreciation_method',
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
           'name' => 'goods_life',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'require' => 'required',
             ),
         ));

         $this->add(array(
           'name' => 'scrap_value',
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
           'name' => 'entered_date',
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
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
	     ),
	     'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 5,
             ),
	 ));

         $this->add(array(
                                'name' => 'submit',
                                'type' => 'Submit',
                                'attributes' => array(
                                    'class'=>'control-label',
                                        'value' => 'Update',
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
