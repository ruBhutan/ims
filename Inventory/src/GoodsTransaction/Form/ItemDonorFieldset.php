<?php

namespace GoodsTransaction\Form;

//use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\ItemDonor;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ItemDonorFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('additemdonor');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ItemDonor());
         
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
           'name' => 'donor_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
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
                  'class' => 'form-control ',
                  'rows' => 5,
                  
             ),
         ));

       $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'text',
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
					'value' => 'Save',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
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
}