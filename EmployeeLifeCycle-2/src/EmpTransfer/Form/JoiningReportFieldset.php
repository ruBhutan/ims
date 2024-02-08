<?php

namespace EmpTransfer\Form;

use EmpTransfer\Model\JoiningReport;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class JoiningReportFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('joiningreport');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new JoiningReport());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
		 $this->add(array(
           'name' => 'joining_report',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'joining_report',
				  'required' => true,
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
			 'joining_report' => array(
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