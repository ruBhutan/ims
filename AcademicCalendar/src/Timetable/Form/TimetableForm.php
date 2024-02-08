<?php

namespace Timetable\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class TimetableForm extends Form
{
	protected $serviceLocator;

	public function __construct($serviceLocator = null, array $options = [], $organisation_id)
     {
		parent::__construct('ajax', $options, $organisation_id);
		
		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator; 
        $this->ajax = $options;
		$this->organisation_id = $organisation_id;
		
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
				'type' => 'Hidden',
		));
		
		$this->add(array(
			'name' => 'day',
			'type' => 'select',
			'options' => array(
				'class' => 'control-label',
				'empty_option' => 'Select a Day',
				'value_options' => array(
					'Monday' => 'Monday',
					'Tuesday' => 'Tuesday',
					'Wednesday' => 'Wednesday',
					'Thursday' => 'Thursday',
					'Friday' => 'Friday',
					'Saturday' => 'Saturday'
					),
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
        		
		$this->add(array(
			'name' => 'classroom',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'from_time',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Time',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'to_time',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Time',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'status',
			'type' => 'select',
			'options' => array(
				'class' => 'control-label',
				'empty_option' => 'Select Status',
				'value_options' => array(
					'Active' => 'Active',
					'In-Active' => 'In Active',
					),
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		
		$this->add(array(
			'name' => 'programmes_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Programme',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectProgrammeTimetable',
				  'options' => $this->getProgrammeList(),
             ),
         ));
		
		$this->add(array(
			'name' => 'academic_modules_allocation_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectModuleTimetable',
				  'options' => array(),
             ),
         ));
		 
		 $this->add(array(
			'name' => 'group',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Section/Group',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectSectionTimetable',
				  'options' => array(),
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
	
	private function getProgrammeList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        //$sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`programme_name` AS `programme_name` FROM `programmes` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $sql       = 'SELECT id, programme_name FROM programmes where organisation_id ="'.$this->organisation_id.'" order by programme_name asc';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['programme_name'];
        }
        return $selectData;
    }
}