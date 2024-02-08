<?php
//used when adding new employees
namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class NewEmployeeDetailForm extends Form
{
	protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
     {
        /*parent::__construct('budgetproposal');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 */
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
			'name' => 'emp_id',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
			),
			'attributes' =>array(
				'class' => 'form-control',
				'required' => true
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
			'name' => 'emp_position_title',
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
			'name' => 'emp_position_level',
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
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success',
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
}