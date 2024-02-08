<?php

namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

use Zend\InputFilter\InputFilterProviderInterface;

class EmployeeJobProfileForm extends Form implements InputFilterProviderInterface
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
                'name' => 'author',
                'type' => 'Hidden'
            ));
            
            $this->add(array(
            'name' => 'employee_details',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));
            
            $this->add(array(
            'name' => 'emp_type_id',
                'type'=> 'Select',
                'options' => array(
                    'empty_option' => 'Please Select Staff Type',
                    'disable_inarray_validator' => true,
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                    'required' => true
                ),
            ));


            $this->add(array(
                'name' => 'organisation_id',
                'type'=> 'select',
                 'options' => array(
                     'empty_option' => 'Select Organisation',
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
            'name' => 'departments_units_id',
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
            'name' => 'major_occupational_group_id',
                'type'=> 'select',
                'options' => array(
                    'empty_option' => 'Please Select a Group',
                    'disable_inarray_validator' => true,
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => true,
                    'id' => 'selectTransferOccupationGroup',
                    'options' => $this->createOccupationalGroup(),
                ),
            ));
                    
            $this->add(array(
            'name' => 'emp_category_id',
                'type'=> 'select',
                'options' => array(
                    'empty_option' => 'Please Select a Category',
                    'disable_inarray_validator' => true,
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => true,
                    'id' => 'selectTransferCategory',
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
                    'id' => 'selectTransferPositionTitle',
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
                    'id' => 'selectTransferPositionLevel',
                    'options' => array(),
                ),
            ));

            $this->add(array(
                'name' => 'teaching_allowance',
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
            'name' => 'increment_type_id',
                'type'=> 'Select',
                'options' => array(
                    'empty_option' => 'Please Select Pay Increment Type',
                    'disable_inarray_validator' => true,
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => true
                ),
            ));

            $this->add(array(
            'name' => 'pay_scale',
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
            'name' => 'status',
                'type'=> 'Select',
                'options' => array(
                    'empty_option' => 'Please Select Staff Status',
                    'disable_inarray_validator' => true,
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => true
                ),
            ));

            $this->add(array(
            'name' => 'reason',
                'type'=> 'Textarea',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'rows' => 3
                ),
            ));

            $this->add(array(
            'name' => 'created',
                'type'=> 'text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

            $this->add(array(
            'name' => 'modified',
                'type'=> 'text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
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
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`organisation_name` AS `organisation_name` FROM `organisation` AS `t1` WHERE t1.id = "'.$this->organisation_id.'"';
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