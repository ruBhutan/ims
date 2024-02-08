<?php

namespace EmpTransfer\Form;

use EmpTransfer\Model\OvcTransferApproval;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OvcTransferApprovalFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('ovcapproval');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new OvcTransferApproval());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
		 $this->add(array(
           'name' => 'ovc_transfer_status',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select an Option',
                 'value_options' => array(
                      'OVC Approved' => 'Approve Transfer',
					  'OVC Rejected' => 'Reject Transfer',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'ovc_transfer_remarks',
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
           'name' => 'rejection_order',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'rejection_order',
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
			 'rejection_order' => array(
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