<?php

namespace EmpTransfer\Form;

use EmpTransfer\Model\TransferStaff;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class TransferStaffFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('transferstaff');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new TransferStaff());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
		 $this->add(array(
           'name' => 'transfer_request_to',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a College/OVC',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'reason_for_transfer',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
				  'rows' => 3,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'spouse_new_organisation',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'document_proof',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'document_proof',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'date_of_request',
            'type'=> 'date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'readonly' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_org_transfer_status',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select',
				 'value_options' => array(
				 		'pending' => 'Pending',
						'approved' => 'Approved',
						'rejected' => 'Rejected'
				),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'value' => 'pending'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_org_transfer_status',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select',
				 'value_options' => array(
				 		'pending' => 'Pending',
						'approved' => 'Approved',
						'rejected' => 'Rejected'
				),
			),
             'attributes' => array(
                  'class' => 'form-control',
				  'value' => 'pending'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_org_transfer_remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_org_transfer_remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit',
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
			 'document_proof' => array(
			 	'required' => true,
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
						'target' => './data/transfer',
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