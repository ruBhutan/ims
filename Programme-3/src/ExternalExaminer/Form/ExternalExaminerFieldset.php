<?php

namespace ExternalExaminer\Form;

use ExternalExaminer\Model\ExternalExaminer;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ExternalExaminerFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('externalexaminer');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ExternalExaminer());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          		 
		  $this->add(array(
           'name' => 'examiner_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control '
             ),
         ));
		 
		 $this->add(array(
           'name' => 'address',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'contact_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'email',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'ab_approval',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
                  'id' => 'single_cal2'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
                  'id' => 'single_cal3'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'rows' => 3
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

        'evidence_file' => array(
        'required' => false,
        'allow_empty' => true,
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
            'target' => './data/externalexaminer',
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