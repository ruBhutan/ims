<?php

namespace Budgeting\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CapitalBudgetReappropriationSelectForm extends Form
{
	public function __construct($name = null, array $options = [])
     {
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
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
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
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT activity_name FROM budget_proposal_capital where budget_proposal_status= "Approved"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['activity_name']] = $res['activity_name'];
        }
        return $selectData;
    }
}