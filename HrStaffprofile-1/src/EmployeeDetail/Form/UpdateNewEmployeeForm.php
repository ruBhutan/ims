<?php
//used when adding new employees
namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class UpdateNewEmployeeForm extends Form implements InputFilterProviderInterface
{

	public function __construct()
     {

		 parent::__construct();
		
		$this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 
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
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		
		$this->add(array(
			'name' => 'emp_gewog',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

		$this->add(array(
			'name' => 'emp_village',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 
		 $this->add(array(
			'name' => 'organisation_id',
			'type'=> 'text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'departments_id',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));


          $this->add(array(
			'name' => 'departments_units_id',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

		
		$this->add(array(
			'name' => 'gender',
			'type' => 'Text',
			'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		
		$this->add(array(
			'name' => 'marital_status',
			'type' => 'Text',
			'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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
			),
		));
		
		$this->add(array(
			'name' => 'religion',
			'type' => 'Text',
			'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
		));
		
		$this->add(array(
			'name' => 'blood_group',
			'type' => 'Text',
			'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
		));
		
		$this->add(array(
			'name' => 'occupational_group',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 $this->add(array(
			'name' => 'emp_type',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		$this->add(array(
			'name' => 'emp_category',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'position_title_id',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'position_level_id',
			'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'recruitment_date',
			'type' => 'Text',
			'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
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
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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


     /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
            'evidence_file' => array(
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
                        'target' => './data/staff_office_order',
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