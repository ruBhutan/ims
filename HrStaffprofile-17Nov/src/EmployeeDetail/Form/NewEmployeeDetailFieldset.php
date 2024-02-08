<?php
//used when adding new employees
//not used as we do not need ad more functionality. Additionally, need to use javascript
namespace EmployeeDetail\Form;

use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewEmployeeDetailFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeedetail');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new NewEmployeeDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
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
				'placeholder' => 'Citizen Identity Card No',
			),
		));
		
		$this->add(array(
			'name' => 'nationality',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' =>' Nationality',
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
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Dzongkhag',
				),
		));
		
		$this->add(array(
			'name' => 'emp_gewog',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Gewog',
			),
		));
		
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
             ),
         ));
		
		$this->add(array(
			'name' => 'gender',
			'type' => 'Select',
			'options' => array(
                 'empty_option' => 'Select a Gender',
				 'class'=>'control-label',
				 'value_options' => array(
				 	'Male' => 'Male',
					'Female' => 'Female'
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'marital_status',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Marital Status',
				),
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
             'name' => array(
                 'required' => false,
             ),
         );
     }
}