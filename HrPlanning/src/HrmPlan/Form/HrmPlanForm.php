<?php

namespace HrmPlan\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class HrmPlanForm extends Form
{
	public function __construct($name = null, array $options = [])
     {
        /*parent::__construct('budgetproposal');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 */
		 parent::__construct('ajax', $options);
		
		$this->adapter1 = $name; 
		$this->ajax = $name; 
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
             'type' => 'HrmPlan\Form\HrmPlanFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
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
				  'id' => 'selectOccupationGroup',
				  'options' => $this->createOccupationalGroup(),
             ),
         ));
		 
		$this->add(array(
			'name' => 'position_category_id',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'selectCategory',
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
				  'id' => 'selectPositionTitle',
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
             'attributes' => array(
                 'type' => 'submit',
                 'value' => 'Send',
             ),
         ));
     }
	 	
	private function createOccupationalGroup()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->adapter1;
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
