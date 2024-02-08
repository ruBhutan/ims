<?php

namespace EmpTraining\Form;

use EmpTraining\Model\StudyReport;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudyReportFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('trainingreport');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudyReport());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'training_details_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
			'name' => 'marks_obtained',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Aggregate Marks Obtained',
				),
		));
		
		$this->add(array(
			'name' => 'study_status',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'award_name',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'remarks',
			'type' => 'textarea',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'rows' => 3
				),
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
                          ),
                      ));
		 		 		 
		 $this->add(array(
                        'name' => 'feedback_form',
                         'type'=> 'file',
                          'options' => array(
                              'class'=>'control-label',
                          ),
                          'attributes' => array(
                               'class' => 'form-control',
                                'id' => 'feedback_form',
                          ),
                      ));
		 
		 $this->add(array(
                        'name' => 'certificates',
                         'type'=> 'file',
                          'options' => array(
                              'class'=>'control-label',
                          ),
                          'attributes' => array(
                               'class' => 'form-control',
                                'id' => 'certificates',
                          ),
                      ));
		 
		 $this->add(array(
                        'name' => 'marksheets',
                        'type'=> 'file',
                          'options' => array(
                              'class'=>'control-label',
                          ),
                          'attributes' => array(
                               'class' => 'form-control',
                                'id' => 'marksheets',
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
                 'joining_report' => array(
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
                                        'target' => './data/training',
                                        'useUploadName' => true,
                                        'useUploadExtension' => true,
                                        'overwrite' => true,
                                        'randomize' => true
                                        ),
                                ),
                        ),
                 ),
                 'feedback_form' => array(
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
                                        'target' => './data/training',
                                        'useUploadName' => true,
                                        'useUploadExtension' => true,
                                        'overwrite' => true,
                                        'randomize' => true
                                        ),
                                ),
                        ),
                 ),
                 'certificates' => array(
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
                                        'target' => './data/training',
                                        'useUploadName' => true,
                                        'useUploadExtension' => true,
                                        'overwrite' => true,
                                        'randomize' => true
                                        ),
                                ),
                        ),
                 ),
                 'marksheets' => array(
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
                                        'target' => './data/training',
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