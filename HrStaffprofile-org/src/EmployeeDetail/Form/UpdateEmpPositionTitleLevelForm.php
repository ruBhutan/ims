<?php

namespace EmployeeDetail\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class UpdateEmpPositionTitleLevelForm extends Form
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
            'name' => 'employee_details_id',
            'type' => 'Text',
            'options' => array(
                'class' => 'control-label',
            ),
            'attributes' =>array(
                'class' => 'form-control',
                // /'required' => true
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
	
}