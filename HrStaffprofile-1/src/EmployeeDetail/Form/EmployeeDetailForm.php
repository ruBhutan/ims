<?php

namespace EmployeeDetail\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class EmployeeDetailForm extends Form
{
	protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
	{
		  // we want to ignore the name passed
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
             'type' => 'EmployeeDetail\Form\EmployeeDetailFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
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
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));

         $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'btn btn-success'
            ),
        ));                
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