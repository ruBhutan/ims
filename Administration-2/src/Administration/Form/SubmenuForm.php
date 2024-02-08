<?php

namespace Administration\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class SubmenuForm extends Form
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
           'name' => 'user_menu_level',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Menu Level',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
                  'id' => 'selectUserMenuLevel',
                  'options' => array(
                        '1' => 'Sub Menu Level 1',
                        '2' => 'Sub Menu Level 2',
                        '3' => 'Sub Menu Level 3',
                  ),
             ),
         ));
         
         $this->add(array(
           'name' => 'user_menu_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Sub Menu',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectUserModule',
                  'options' => array(),
                  'required' => 'required',
             ),
         ));
         
         $this->add(array(
           'name' => 'menu_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));
         
         $this->add(array(
           'name' => 'menu_weight',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));
         
                    
         $this->add(array(
           'name' => 'menu_description',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 3
             ),
         ));

         $this->add(array(
           'name' => 'menu_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

          $this->add(array(
           'name' => 'parent_menu',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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
                    'value' => 'Add Sub Menu',
                    'id' => 'submitbutton',
                        'class' => 'btn btn-success',
                ),
          ));
     }
}