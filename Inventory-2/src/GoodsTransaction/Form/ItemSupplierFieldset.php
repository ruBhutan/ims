<?php

namespace GoodsTransaction\Form;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\ItemSupplier;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ItemSupplierFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('itemsupplier');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ItemSupplier());
         
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
           'name' => 'supplier_name',
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
           'name' => 'supplier_license_no',
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
           'name' => 'supplier_tpn_no',
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
           'name' => 'supplier_bank_acc_no',
            'type'=> 'number',
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
           'name' => 'supplier_contact_no',
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
           'name' => 'from_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                  'id' => 'single_cal2',
             ),
         ));


       $this->add(array(
           'name' => 'to_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                   'id' => 'single_cal3'
             ),
         ));


       $this->add(array(
           'name' => 'supplier_details_id',
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
           'name' => 'supplier_status',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        '0' => 'Select Supplier Status',
                        'Active' => 'Active',
                        'Inactive' => 'Black List'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

       $this->add(array(
           'name' => 'supporting_documents',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'supporting_documents',
             ),
         ));

       $this->add(array(
           'name' => 'supplier_address',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 5,
                  'required' => 'required',
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
     }
	 
	  /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
       'supporting_documents' => array(
        'required' => false,
        'validators' => array(
          array(
          'name' => 'FileUploadFile',
          ),
          array(
                'name' => 'Zend\Validator\File\Size',
                'options' => array(
                    'min' => '10kB',
                    'max' => '2MB',
                ),
            ),
            array(
                'name' => 'Zend\Validator\File\Extension',
                'options' => array(
                    'extension' => ['png','jpg','jpeg','pdf'],
                ),
            ),
        ),
        'filters' => array(
          array(
          'name' => 'FileRenameUpload',
          'options' => array(
            'target' => './data/item_supplier_documents',
            'useUploadName' => true,
            'useUploadExtension' => true,
            'overwrite' => true,
            'randomize' => true
            ),
          ),
        ),
       ),
         );
     }
}

