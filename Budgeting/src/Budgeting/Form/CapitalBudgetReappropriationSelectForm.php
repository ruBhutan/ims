<?php

namespace Budgeting\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CapitalBudgetReappropriationSelectForm extends Form
{
    protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
     {
		 parent::__construct('ajax', $options);
		
         $this->serviceLocator = $serviceLocator; 
         $this->ajax = $serviceLocator; 
         $this->ajax = $options;

         //the following are so that we can get the organisation id
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
		    $this->organisation_id = $this->getOrganisationId($this->username);
		
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
            'attributes' => array(
            'type' => 'Hidden',
             ),   
        ));

        $this->add(array(
            'name' => 'budget_type',
             'type'=> 'text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
              ),
          ));
		
		$this->add(array(
           'name' => 'from_activity_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Activity Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectFromActivityName',
				  'options' => $this->createActivityName(),
             ),
         ));
         
        $this->add(array(
           'name' => 'from_broad_head_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Broad Head Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectFromBroadHeadName',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_object_code_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Object Code',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectFromObjectCode',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_activity_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Activity Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectToActivityName',
				  'options' => $this->createActivityName(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'to_broad_head_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Broad Head Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectToBroadHeadName',
				  'options' => array(),
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'to_object_code_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Object Code',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectToObjectCode',
				  'options' => array(),
             ),
         ));


		 $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

         $this->add(array(
            'name' => 'status',
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
	
	private function createActivityName()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = 'SELECT id, activity_name FROM budget_proposal_capital where budget_proposal_status= "Approved" AND organisation_id="'.$this->organisation_id.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['activity_name'];
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
}