<?php
namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class NominateSubStoreForm extends Form
 {

     public function __construct($name = null, array $options = [])
     {
        parent::__construct('ajax', $options);

        $this->adapter = $name;
        $this->ajax = $name;
        $this->ajax = $options;
        //$this->organisation_id = $organisation_id;
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

         //the following are so that we can get the organisation id
        $user_session = new Container('user');
        $this->username = $user_session->username;
        $this->departments_id = $this->getDepartmentId($this->username);
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
      /*  $this->add(array(
             'type' => 'GoodsTransaction\Form\DeptGoodsFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));*/

         $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));


         $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Responsible Staff',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                 // 'id' => 'selectNominateDepartmentName',
                  'options' => $this->createNominateStaffList(),
                  
             ),
         ));



        $this->add(array(
           'name' => 'departments_id',
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
           'name' => 'nomination_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                  'id' => 'single_cal2'
             ),
         ));


      $this->add(array(
           'name' => 'status',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                        '0' => 'Select Nominee Status',
                        'Active' => 'Active',
                        'Inactive' => 'Inactive'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));

       $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                                    'class'=>'control-label',
                    'value' => 'Nominate',
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

    private function createNominateStaffList()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT `t1`.`id` AS `id`, `t1`.`first_name` AS `first_name`,  `t1`.`middle_name` AS `middle_name`,  `t1`.`last_name` AS `last_name`,  `t1`.`emp_id` AS `emp_id` FROM `employee_details` AS `t1` WHERE t1.departments_id = '. $this->departments_id;;
        //WHERE organisation_id ="'.$this->organisation_id.'"';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['first_name'].' '.$res['middle_name'].' '.$res['last_name'].' ('.$res['emp_id'].')';
        }
        return $selectData;
    }

    private function getDepartmentId($username)
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT `t1`.`departments_id` AS `departments_id` FROM `employee_details` as `t1` WHERE t1.emp_id = '. $this->username;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

       foreach ($result as $res) {
            $departmentId = $res['departments_id'];
        }
        return $departmentId;
    }
 }
