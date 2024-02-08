<?php

namespace Budgeting\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class CapitalBudgetProposalForm extends Form
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
             'type' => 'Budgeting\Form\CapitalBudgetProposalFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));
		 
		  $this->add(array(
           'name' => 'broad_head_name_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Broad Head Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectBroadHeadName',
				  'options' => $this->createValueBroadHeadName(),
             ),
         ));
		 
		 $this->add(array(
           'name' => 'object_code_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Object Code',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectObjectCode',
				  'options' => array(),
             ),
         ));
		 
         $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
         ));
     }
	 
	 private function createValueBroadHeadName()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT id, broad_head_name FROM broad_head_name';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['broad_head_name'];
        }
        return $selectData;
    }
}