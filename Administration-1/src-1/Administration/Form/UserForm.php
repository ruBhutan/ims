<?php

namespace Administration\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class UserForm extends Form
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
           'name' => 'region',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Region',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'id' => 'selectUserRegion',
                  'options' => $this->createUserRegion(),
             ),
         ));

        /*$this->add(array(
           'name' => 'user_type_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select User Type',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
                  'id' => 'selectUserMenuLevel',
                  'options' => array(
                        '1' => 'STAFF',
                        '2' => 'STUDENT',
                        '3' => 'STUDENT_PARENT',
                        '4' => 'JOB_APPLICANT',
                  ),
             ),
         ));*/

        $this->add(array(
           'name' => 'username',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Staff',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectUserName',
                  'options' => array(),
                  'required' => 'required',
             ),
         ));

        $this->add(array(
           'name' => 'role',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Role',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));

        $this->add(array(
           'name' => 'password',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
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
                    'value' => 'Add New User',
                    'id' => 'submitbutton',
                        'class' => 'btn btn-success',
                ),
          ));
          
          $this->add(array(
            'name' => 'update',
             'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Update User',
                    'id' => 'submitbutton',
                        'class' => 'btn btn-success',
                ),
          ));

     }


     private function createUserRegion()
     {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT * FROM organisation";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['organisation_name'];
        }
        return $selectData;
     }
}