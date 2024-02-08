<?php

namespace Vacancy\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class SelectedApplicantForm extends Form implements InputFilterProviderInterface
{
    protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
     {
         // we want to ignore the name passed
        parent::__construct('ajax', $options);

        $this->serviceLocator = $serviceLocator;
        $this->ajax = $serviceLocator;
        $this->ajax = $options;
		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		
		$this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
		
		/*$this->add(array(
             'type' => 'Vacancy\Form\SelectedApplicantFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));*/

         $this->add(array(
            'name' => 'id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'job_applicant_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'emp_job_applications_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
			'name' => 'emp_id',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
			),
			
		));
		                           
		$this->add(array(
			'name' => 'first_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter FirstName --',
				'required' => true
			),
			
		));
		
		$this->add(array(
			'name' => 'middle_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter MiddleName-- ',
			),
		));
		
		$this->add(array(
			'name' => 'last_name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => '--Enter LastName--',
				),
		));
	   
		$this->add(array(
			'name' => 'cid',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Citizen Identity Card No',
			),
        ));
        
        $this->add(array(
            'name' => 'country',
             'type'=> 'Text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control',
              ),
              ));
         
           $this->add(array(
            'name' => 'nationality',
             'type'=> 'Text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control',
              ),
              ));

              $this->add(array(
                'name' => 'date_of_birth',
                'type' => 'Text',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));


         $this->add(array(
			'name' => 'religion',
			'type' => 'Select',
			'options' => array(
                 'empty_option' => 'Select a Religion',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
             ),
		));
		
		$this->add(array(
			'name' => 'blood_group',
			'type' => 'Select',
			'options' => array(
                 'empty_option' => 'Select a Blood Group',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
             ),
		));
		 
		 $this->add(array(
			'name' => 'departments_units_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Unit',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectUnits',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'organisation_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select an Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectOrganisationName',
				  'options' => $this->createOrganisationName(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'departments_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Department',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectDepartments',
				  'options' => array(),
             ),
         ));

         $this->add(array(
			'name' => 'occupational_group',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Group',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectOccupationGroup',
				  'options' => $this->createOccupationalGroup(),
             ),
         ));
		 
		$this->add(array(
			'name' => 'emp_category',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectCategory',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'position_title_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectPositionTitle',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'position_level_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
				  'id' => 'selectPositionLevel',
				  'options' => array(),
             ),
         ));

         $this->add(array(
			'name' => 'emp_type',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select Employee Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true,
             ),
         ));

         $this->add(array(
			'name' => 'status',
			'type' => 'Text',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

            $this->add(array(
                'name' => 'submission_status',
                'type' => 'Select',
                'options' => array(
                     'empty_option' => 'Select a Submission Status',
                     'disable_inarray_validator' => true,
                     'class'=>'control-label',
                     'value_options' => array(
                        'Submitted to OVC' => 'Submit to OVC',
                        'Do Not Submit to OVC' => 'Do Not Submit to OVC'
                    )
                 ),
                 'attributes' => array(
                      'class' => 'form-control ',
                      'required' => true,
                 ),
            ));
		
		$this->add(array(
			'name' => 'recruitment_date',
			'type' => 'Text',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
                    'id' => 'single_cal3',
                ),
            ));

		$this->add(array(
			'name' => 'emp_resignation_id',
			'type' => 'Text',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

            $this->add(array(
                'name' => 'office_order_no',
                'type' => 'Text',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
            $this->add(array(
                'name' => 'office_order_date',
                'type' => 'Text',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control fa fa-calender-o',
                        'required' => 'required',
                        'id' => 'single_cal1',
                    ),
                ));
    
            $this->add(array(
                'name' => 'evidence_file',
                'type' => 'file',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
            $this->add(array(
                'name' => 'announcement_doc',
                'type' => 'file',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
            $this->add(array(
                'name' => 'shortlist_doc',
                'type' => 'file',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
            $this->add(array(
                'name' => 'selection_doc',
                'type' => 'file',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
            $this->add(array(
                'name' => 'minutes_doc',
                'type' => 'file',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
            $this->add(array(
                'name' => 'emp_application_form_doc',
                'type' => 'file',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
    
                $this->add(array(
                    'name' => 'emp_academic_transcript_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
    
                $this->add(array(
                    'name' => 'emp_training_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
    
                $this->add(array(
                    'name' => 'emp_cid_wp_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
    
                $this->add(array(
                    'name' => 'emp_security_cl_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
    
                $this->add(array(
                    'name' => 'emp_medical_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
    
    
                $this->add(array(
                    'name' => 'emp_no_objec_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
    
                    $this->add(array(
                        'name' => 'appointment_order_doc',
                        'type' => 'file',
                        'options' => array(
                                'class'=>'control-label',
                            ),
                            'attributes' => array(
                                'class' => 'form-control',
                            ),
                        ));
    
                $this->add(array(
                    'name' => 'others_doc',
                    'type' => 'file',
                    'options' => array(
                            'class'=>'control-label',
                        ),
                        'attributes' => array(
                            'class' => 'form-control',
                        ),
                    ));
                
    
    
            $this->add(array(
                'name' => 'new_employee_details_id',
                'type' => 'text',
                'options' => array(
                        'class'=>'control-label',
                    ),
                    'attributes' => array(
                        'class' => 'form-control',
                    ),
                ));
		 		 
		 $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
             )
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
	
	private function createOrganisationName()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, organisation_name FROM organisation';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
    }


    private function createOccupationalGroup()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, major_occupational_group FROM major_occupational_group';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['major_occupational_group'];
        }
        return $selectData;
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
              'announcement_doc' => array(
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
                         'target' => './data/new_employee_docs',
                         'useUploadName' => true,
                         'useUploadExtension' => true,
                         'overwrite' => true,
                         'randomize' => true
                         ),
                     ),
                 ),
              ),
              'shortlist_doc' => array(
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
                         'target' => './data/new_employee_docs',
                         'useUploadName' => true,
                         'useUploadExtension' => true,
                         'overwrite' => true,
                         'randomize' => true
                         ),
                     ),
                 ),
              ),
              'selection_doc' => array(
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
                         'target' => './data/new_employee_docs',
                         'useUploadName' => true,
                         'useUploadExtension' => true,
                         'overwrite' => true,
                         'randomize' => true
                         ),
                     ),
                 ),
              ),
             'minutes_doc' => array(
                  'required' => false,
                                 'allow_empty' => true,
                 'validators' => array(
                     array(
                     'name' => 'FileUploadFile',
                     ),
                 ),
                 'filters' => array(
                     array(
                     'name' => 'FileRenameUpload',
                     'options' => array(
                         'target' => './data/new_employee_docs',
                         'useUploadName' => true,
                         'useUploadExtension' => true,
                         'overwrite' => true,
                         'randomize' => true
                         ),
                     ),
                 ),
              ),
 
             'emp_application_form_doc' => array(
                  'required' => false,
                                 'allow_empty' => true,
                 'validators' => array(
                     array(
                     'name' => 'FileUploadFile',
                     ),
                 ),
                 'filters' => array(
                     array(
                     'name' => 'FileRenameUpload',
                     'options' => array(
                         'target' => './data/new_employee_docs',
                         'useUploadName' => true,
                         'useUploadExtension' => true,
                         'overwrite' => true,
                         'randomize' => true
                         ),
                     ),
                 ),
              ),
 
              'emp_academic_transcript_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'emp_training_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'emp_cid_wp_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'emp_security_cl_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'emp_medical_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'emp_no_objec_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'appointment_order_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'others_doc' => array(
                 'required' => false,
                                'allow_empty' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/new_employee_docs',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
 
             'position_level_id' => array(
                 'required' => true,
                 ),
 
          );
      }
}