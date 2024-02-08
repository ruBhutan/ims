<?php

namespace CounselingService\Form;

use CounselingService\Model\CounselingNotes;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CounselingNotesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('counselingnotes');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CounselingNotes());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'counselor',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 
		 $this->add(array(
           'name' => 'notes',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'rows' => 5
             ),
         ));
		 
		 $this->add(array(
           'name' => 'date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                'class' => 'form-control fa fa-calender-o',
                'required' => 'required',
                'id' => 'single_cal2',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'applicant_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'applicant_type',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'scheduled_counseling_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'documents',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'documents',
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
        'required' => false,
        'validators' => array(
          array(
          'name' => 'FileUploadFile',
          ),
        ),
        'filters' => array(
          array(
          'name' => 'FileRenameUpload',
          'options' => array(
            'target' => './data/counseling_documents',
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