<?php
namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Db\Adapter\Adapter;

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class ItemSubCategoryForm extends Form
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
             'type' => 'GoodsTransaction\Form\ItemSubCategoryFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

         $this->add(array(
            'name' => 'major_class_id',
             'type'=> 'Select',
              'options' => array(
                  'empty_option' => 'Select Item Major Class',
                  'disable_inarray_validator' => true,
                  'class'=>'control-label',
     
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'id' => 'selectItemSubCategoryMajorClass',
                   'options' => $this->createItemMajorClass(),
                   'required' => 'required',
              ),
          ));

          $this->add(array(
            'name' => 'item_category_id',
             'type'=> 'Select',
              'options' => array(
                  'empty_option' => 'Select Item Category Category',
                  'disable_inarray_validator' => true,
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'id' => 'selectItemSubCategoryCategory',
                   'options' => array(),
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
     }

     private function createItemMajorClass()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, major_class FROM item_major_class';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['major_class'];
        }
        return $selectData;
    } 

 }
