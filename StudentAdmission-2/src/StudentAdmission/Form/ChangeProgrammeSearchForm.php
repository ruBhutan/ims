<?php

namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class ChangeProgrammeSearchForm extends Form
{
    protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [])
	{

		// we want to ignore the name passed
        parent::__construct('ajax', $options);

        $this->serviceLocator = $serviceLocator;
        $this->ajax = $serviceLocator;
        $this->ajax = $options;
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));

		$this->add(array(
            'name' => 'programme',
            'type'=> 'Select',
            'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Select Programme',
                'value_options' => array(
                    '0' => 'Select'
                )
            ),
           'attributes' => array(
                'class' => 'form-control',
                'value' => '0', // Set selected to 0
                'required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'programme_year',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Year',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectYear',
                  'options' => $this->createProgrammeYear(),
                  'required' => 'required'
             ),
        ));


        $this->add(array(
            'name' => 'semester',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Semester',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectSemester',
                  'options' => array(),
                  'required' => 'required'
             ),
        ));


        $this->add(array(
            'name' => 'year',
            'type' => 'text',
            'options' => array(
                'class' => 'control-label',
                ),
            'attributes' =>array(
                'class' => 'form-control',
                'placeholder' => 'Programme Change Year',
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
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));            
	}


    private function createProgrammeYear()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, year FROM programme_year';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['year'];
        }
        return $selectData;
    }

}