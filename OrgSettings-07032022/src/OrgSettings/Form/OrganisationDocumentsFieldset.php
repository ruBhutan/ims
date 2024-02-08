<?php

namespace OrgSettings\Form;

use OrgSettings\Model\OrganisationDocuments;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OrganisationDocumentsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('organisationdocuments');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new OrganisationDocuments());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));
          
         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
           'name' => 'documents',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
                  'id' => 'documents',
             ),
         ));

         $this->add(array(
            'name' => 'document_type',
             'type'=> 'text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
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
                  'required' => true
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
              'documents' => array(
                  'required' => true,
                 'validators' => array(
                     array(
                     'name' => 'FileUploadFile',
                     ),
                 ),
                 'filters' => array(
                     array(
                     'name' => 'FileRenameUpload',
                     'options' => array(
                         'target' => './data/organisation_documents',
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