<?php

namespace StudentSuggestions\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class SuggestionCommitteeForm extends Form
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
             'type' => 'StudentSuggestions\Form\SuggestionCommitteeFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

        $this->add(array(
           'name' => 'organisation',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Organisation',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectSuggCommOrganisation',
                  'options' => $this->getOrganisationList(),
                  'required' => 'required',
             ),
         ));

        $this->add(array(
            'name' => 'employee_details_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Please Select Staff',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectSuggCommitteStaff',
                  'options' => array(),
                  'required' => 'required'
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

     private function getOrganisationList()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, organisation_name FROM organisation';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
    }
}