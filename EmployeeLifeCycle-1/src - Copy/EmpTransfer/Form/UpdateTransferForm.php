<?php

namespace EmpTransfer\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class UpdateTransferForm extends Form
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
                
		$this->setAttributes(array(
				'class' => 'form-horizontal form-label-left',
		));
		
        $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
		 $this->add(array(
           'name' => 'transfer_order_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'transfer_order_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => true,
                  'id' => 'single_cal2'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'joining_date',
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
           'name' => 'previous_working_agency',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		

         $this->add(array(
            'name' => 'new_working_agency',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Org. Transferred To',
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
            'name' => 'new_departments_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a New Department',
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
            'name' => 'new_departments_units_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a New Unit',
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
           'name' => 'previous_position_category',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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
				  'id' => 'selectTransferOccupationGroup',
				  'options' => $this->createOccupationalGroup(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'new_position_category',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectTransferCategory',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'previous_position_title',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'new_position_title',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectTransferPositionTitle',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'previous_position_level',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'new_position_level',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectTransferPositionLevel',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'previous_pay_scale',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'new_pay_scale',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Pay Scale',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectTransferPayScale',
				  'options' => array(),
             ),
         ));
                 
        $this->add(array(
           'name' => 'new_pay_allowance',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Teaching Allowance',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectTransferTeachingAllowance',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'reasons',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'transfer_order_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'text',
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
             'rejection_order' => array(
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
                        'target' => './data/transfer',
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