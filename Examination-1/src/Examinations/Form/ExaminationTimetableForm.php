<?php

namespace Examinations\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ExaminationTimetableForm extends Form
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
			'name' => 'organisation_id',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
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
				  'id' => 'selectProgrammeName',
				  'options' => $this->getProgrammeList(),
				  'required' => true,
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
				  'id' => 'selectModuleName',
				  'options' => array(),
				  'required' => true,
             ),
         ));
              
		$this->add(array(
			'name' => 'examination_hall_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Hall',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		
		$this->add(array(
			'name' => 'start_time',
			'type'=> 'time',
             'options' => array(
                 'class'=>'control-label',
				 'format' => 'H:i'
             ),
             'attributes' => array(
				  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		
		$this->add(array(
			'name' => 'end_time',
			'type'=> 'time',
             'options' => array(
                 'class'=>'control-label',
				 'format' => 'H:i'
             ),
             'attributes' => array(
				  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		
		$this->add(array(
			'name' => 'exam_date',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control has-feedback-left',
				  'id' => 'single_cal4',
				  'required' => true
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
				'class' => 'btn btn-success'
				),
		));
	}
	
	private function getProgrammeList()
    {
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`programme_name` AS `programme_name` FROM `programmes` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['programme_name'];
        }
        return $selectData;
    }
}
