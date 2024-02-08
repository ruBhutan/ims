<?php

namespace FeeSubCategory\Form;

use Zend\Form\Form;

class AddFeeSubCategoryForm extends Form
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
           'name' => 'fees_category',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                          '1' => '--Select--',
                          '2' => 'Sports',
                          '3' => 'College Fees',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
           $this->add(array(
           'name' => 'fee_sub_categoryname',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
            $this->add(array(
           'name' => 'amount',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
             ),
         ));
          
               $this->add(array(
           'name' => 'fee_type',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                          '1' => '--Select Type--',
                          '2' => 'Annual',
                          '3' => 'Bi-Annual',
                          '4' => 'Tri-Annual',
                          '5' => 'Bi-Annual',
                          '6' => 'Quaterly',
                          '7' => 'Monthly',
                          '8' => 'One-Time',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 'value' =>''//set selected to '1'
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
}