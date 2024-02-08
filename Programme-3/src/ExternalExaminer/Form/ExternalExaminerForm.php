<?php

namespace ExternalExaminer\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class ExternalExaminerForm extends Form
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
           'name' => 'organisation_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'examinerOrganisation',
				  'required' => true,
				  'options' => $this->createOrganisationList(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programmes_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Programme',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'examinerProgramme',
				  'required' => true,
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
             'type' => 'ExternalExaminer\Form\ExternalExaminerFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
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
	 
	 private function createOrganisationList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, organisation_name FROM organisation';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            if(!preg_match('/Chancellor/', $res['organisation_name']))
				$selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
    }
}