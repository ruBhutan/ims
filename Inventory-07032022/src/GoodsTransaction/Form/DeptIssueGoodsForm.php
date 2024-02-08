<?php
namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class DeptIssueGoodsForm extends Form
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
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));


       $this->add(array(
           'name' => 'departments_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Division/ Section',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
                 'required' => 'required',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectAdhocDepartmentName',
                  'options' => $this->createAdhocDepartmentTypeList(),
                  'required' => 'required',
             ),
         ));

       $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Select',
             'options' => array(
               // 'empty_option' => 'Select Responsible Staff',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectAdhocDepartmentStaff',
                  'options' => array(),
                  'required' => 'required',
             ),
         ));


       $this->add(array(
           'name' => 'department_goods_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Item Name',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));

       $this->add(array(
           'name' => 'goods_issued_by',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

       $this->add(array(
           'name' => 'emp_quantity',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));

       $this->add(array(
           'name' => 'goods_code',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

       $this->add(array(
           'name' => 'date_of_issue',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

       $this->add(array(
           'name' => 'issue_goods_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

       $this->add(array(
           'name' => 'goods_issued_remarks',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows' => 5,
                  'required' => 'required',
             ),
         ));

       $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));
         
         $this->add(array(
           'name' => 'dept_quantity',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 // 'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
           'name' => 'goods_received_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 // 'readonly' => 'readonly',
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
                                    'class'=>'control-label',
                    'value' => 'Add',
                    'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
                    ),
                
                ));         
     }

     private function createAdhocDepartmentTypeList()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`unit_name` AS `unit_name` FROM `department_units` AS  `t1`, `departments` AS `t2` WHERE `t2`.`id` = `t1`.`departments_id` AND t2.organisation_id = '. $this->organisation_id;;
        //WHERE organisation_id ="'.$this->organisation_id.'"';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['unit_name'];
        }
        return $selectData;
    }


      private function createAdhocGoodsIssueSubCategoryType()
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
