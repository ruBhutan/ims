<?php

namespace DocumentFiling\Form;

use DocumentFiling\Model\FilingDocument;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class FilingDocumentFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     { 
         // we want to ignore the name passed
        parent::__construct('filingdocument');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new FilingDocument());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'filing_details',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => 'required'
             ),
         ));
        
        $this->add(array(
           'name' => 'meeting_type_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Please Select Record Type',
                 'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
        ));

         $this->add(array(
           'name' => 'filing_date',
            'type'=> 'Text',

             'options' => array(
                 'class'=>'control-label',     
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                'class' => 'form-control',
                'placeholder'=>'yyyy-mm-dd',
                'id' => 'single_cal3'
             ),
         ));
		 
		$this->add(array(
           'name' => 'recorded_by',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
		 
        $this->add(array(
           'name' => 'evidence_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                 'class' => 'form-control ',
                 'id' => 'evidence_file',
                 'required' => true
             ),
         ));

        $this->add(array(
            'name' => 'search',
             'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Search',
                    'id' => 'submitbutton',
                        'class' => 'btn btn-success',
                ),
          ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit',
					'id' => 'searchbutton',
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
            'evidence_file' => array(
                'required' => true,
                'allow_empty' => true,
                'validators' => array(
                    array(
                        'name' => 'FileUploadFile',
                    ),
                    array(
                    'name' => 'Zend\Validator\File\Size',
                    'options' => array(
                      'min' => '10kB',
                      'max' => '30MB',
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
                            'target' => './data/departmentfilingfolder',
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
