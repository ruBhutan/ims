<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\UpdateAurgGrant;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UpdateAurgGrantFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurggrant');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UpdateAurgGrant());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'aurg_grant_id',
              'type' => 'Hidden'  
         ));
          
		  
		  $this->add(array(
           'name' => 'aurg_research_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'aurg_remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3,
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'aurg_evidence_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'aurg_evidence_file',
				  'required' => true
             ),
         ));

         $this->add(array(
           'name' => 'aurg_update_date',
            'type'=> 'Text',
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
             'aurg_evidence_file' => array(
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
                        'target' => './data/research',
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