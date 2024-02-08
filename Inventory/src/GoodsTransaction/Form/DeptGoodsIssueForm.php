<?php
namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class DeptGoodsIssueForm extends Form
 {

    protected $goods_id;

     public function __construct($goods_id)
     {
        parent::__construct('deptgoodsissue');

        $this->goods_id = $goods_id;
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        foreach($this->goods_id as $id){
          $this->add(array(
           'name' => 'dept_quantity'.$id,
            'type'=> 'Number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
        }
        
         
      /*  $this->add(array(
             'type' => 'GoodsTransaction\Form\DeptGoodsFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
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
             ),
         ));

         $this->add(array(
           'name' => 'goods_received_by',
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
           'name' => 'departments_id',
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
           'name' => 'dept_quantity',
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
         */

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
 }
