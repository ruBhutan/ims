<?php

namespace Alumni\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class RegisteredMemberSearchForm extends Form
{
	protected $username;
	protected $organisation_id;
	
	public function __construct($name = null, array $options = [])
     {
        
		parent::__construct('ajax', $options);
		 
		$this->adapter = $name; 
		$this->ajax = $name; 
        $this->ajax = $options;
		
		//the following are so that we can get the organisation id
		$user_session = new Container('user');
        $this->username = $user_session->username;
		$this->organisation_id = $this->getOrganisationId($this->username);
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));

		$this->add(array(
           'name' => 'programme',
            'type'=> 'select',
             'options' => array(
			 	
                 'empty_option' => 'Please Select a Programme',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				 // 'id' => 'selectProgrammeByTutor',
				  'options' => $this->getProgrammeList(),
				  'required' => 'required',
             ),
         ));
		
		$this->add(array(
			'name' => 'name',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Member Name',
				),
		));
		
		$this->add(array(
			'name' => 'graduation_year',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'placeholder' => 'Graduation Year',
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
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));           
	}

	private function getProgrammeList()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`programme_name` AS `programme_name` FROM `alumni_programmes` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['programme_name'];
        }
        return $selectData;
    }
	
	private function getOrganisationId($username)
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'"';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $organisationId = $res['organisation_id'];
        }
        return $organisationId;
    }
}