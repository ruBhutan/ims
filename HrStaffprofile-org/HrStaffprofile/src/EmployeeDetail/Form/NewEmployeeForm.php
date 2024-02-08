<?php
//used when adding new employees
namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewEmployeeForm extends Form implements InputFilterProviderInterface
{
	protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
     {

		 parent::__construct('ajax', $options);
		
		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        $this->ajax = $options;
		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

         //the following are so that we can get the organisation id
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->organisation_id = $this->getOrganisationId($this->username);
		 
		 $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

          $this->add(array(
             'name' => 'id',
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
				'required' => true
			),
		));

		$this->add(array(
           'name' => 'country',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Country',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
             ));
		
		  $this->add(array(
           'name' => 'nationality',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Nationality',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'value' => '23',
                  'required' => 'required',
             ),
             ));

		$this->add(array(
			'name' => 'date_of_birth',
			'type' => 'Text',
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
			'name' => 'emp_house_no',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'House No.',
			),
		));
		
		$this->add(array(
			'name' => 'emp_thram_no',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Thram No.',
			),
		));
		
		$this->add(array(
			'name' => 'emp_dzongkhag',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Dzongkhag',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
				  'id' => 'selectDzongkhag',
				  'options' => $this->createDzongkhag(),
             ),
         ));
		
		$this->add(array(
			'name' => 'emp_gewog',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Gewog',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
				  'id' => 'selectGewog',
				  'options' => array(),
             ),
         ));
		 /*
		 $this->add(array(
			'name' => 'emp_village',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Village',
			),
		));
		*/
		$this->add(array(
			'name' => 'emp_village',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Village',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
				  'id' => 'selectVillage',
				  'options' => array(),
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
				  'required' => true,
				  'id' => 'selectDepartments',
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
				  'required' => true,
				  'id' => 'selectOrganisationName',
				  'options' => $this->createOrganisationName(),
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
				  'required' => true,
				  'id' => 'selectUnits',
				  'options' => array(),
             ),
         ));

		
		$this->add(array(
			'name' => 'gender',
			'type' => 'Select',
			'options' => array(
                 'empty_option' => 'Select a Gender',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		
		$this->add(array(
			'name' => 'marital_status',
			'type' => 'Select',
			'options' => array(
                 'empty_option' => 'Select Marital Status',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
		));
		
		$this->add(array(
			'name' => 'phone_no',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'required' => false,
				'placeholder' => 'Phone No.',
			),
		));
		
		$this->add(array(
			'name' => 'email',
			'type' => 'email',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'id' => 'email',
				'placeholder' => 'e-mail',
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
					'timeout' => 1200
             	)
	     )
         ));

		
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success',
				),
			));                		
	}
	
	private function createDepartmentId()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`department_name` AS `department_name` FROM `departments` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['department_name'];
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
	
	private function createDzongkhag()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, dzongkhag_name FROM dzongkhag';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['dzongkhag_name'];
        }
        return $selectData;
    }


    private function getOrganisationId($username)
    {
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'"';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $organisationId = $res['organisation_id'];
        }
        return $organisationId;
    }


    private function createOrganisationName()
    {
        // You probably want to get those from the Database as in previous example
        if($this->organisation_id == 1){
        	$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
	        $sql       = 'SELECT id, organisation_name FROM organisation';
	        $statement = $dbAdapter1->query($sql);
	        $result    = $statement->execute();
	        $selectData = array();

	        foreach ($result as $res) {
            	$selectData[$res['id']] = $res['organisation_name'];
        	}
        }else{
        	$organisation_id = $this->organisation_id; 

        	$dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
	        $sql       = "SELECT `id`, `organisation_name` FROM `organisation` WHERE `id` = '$organisation_id'";
	        $statement = $dbAdapter1->query($sql);
	        $result    = $statement->execute();
	        $selectData = NULL;

	        foreach ($result as $res) {
            	$selectData[$res['id']] = $res['organisation_name'];
        	}
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