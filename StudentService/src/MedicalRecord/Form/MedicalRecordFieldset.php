<?php

namespace MedicalRecord\Form;

use MedicalRecord\Model\MedicalRecord;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class MedicalRecordFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('medicalrecord');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new MedicalRecord());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'from_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                'class' => 'form-control fa fa-calendar-o',
                'required' => true,
                'id' => 'single_cal3'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                'class' => 'form-control fa fa-calendar-o',
                'required' => true,
                'id' => 'single_cal4'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'medical_problem',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
				  'rows' => '3',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => '3',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'medical_proof',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                   'id' => 'medical_proof',
				  'required' => false
             ),
         ));
		 
		 $this->add(array(
             'name' => 'student_id',
              'type' => 'Hidden'  
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
            'medical_proof' => array(
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
                       'target' => './data/medical_file',
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