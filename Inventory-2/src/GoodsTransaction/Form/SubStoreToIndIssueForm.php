<?php
namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class SubStoreToIndIssueForm extends Form
 {

    protected $goods_id;

     public function __construct($goods_id)
     {
        parent::__construct('depttoindgoodsissue');

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
           'name' => 'emp_quantity'.$id,
            'type'=> 'Number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));
        }


        foreach($this->goods_id as $id){
          $this->add(array(
           'name' => 'goods_code'.$id,
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
        }
        

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
