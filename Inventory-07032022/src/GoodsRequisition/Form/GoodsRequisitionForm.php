<?php

namespace GoodsRequisition\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class GoodsRequisitionForm extends Form
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

         /*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->organisation_id = $this->getOrganisationId($this->username);

         
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));


         $this->add(array(
             'type' => 'GoodsRequisition\Form\GoodsRequisitionFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
           'name' => 'item_sub_category_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Sub Category Type',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectRequisitionItemSubCategory',
                  'options' => $this->createItemSubCategoryType(),
				  'required' => 'required',
             ),
         ));



        $this->add(array(
           'name' => 'item_name_id',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select Item',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectRequisitionItemName',
                  'options' => array(),
				  'required' => 'required',
             ),
         ));

        $this->add(array(
           'name' => 'item_in_stock',
            'type'=> 'Select',
             'options' => array(
               // 'empty_option' => 'Please Select Item',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectRequisitionStockQuantity',
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
     }


        private function createItemSubCategoryType()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`sub_category_type` AS `sub_category_type` FROM `item_sub_category` AS `t1` WHERE t1.organisation_id = '. $this->organisation_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['sub_category_type'];
        }
        return $selectData;
    }


    private function getOrganisationId($username)
    {
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `employee_details` as `t1` WHERE t1.emp_id = "'. $this->username.'"';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $organisationId = $res['organisation_id'];
        }
        return $organisationId;
    }
}
