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

class GoodsReceivedDonationForm extends Form
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
             'type' => 'GoodsTransaction\Form\GoodsReceivedDonationFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         ));

        $this->add(array(
           'name' => 'item_category_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Select Item Category Type',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectGoodsReceivedDonationCategory',
                  'options' => $this->createDonationItemCategoryType(),
             ),
         ));

        $this->add(array(
           'name' => 'item_sub_category_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Select Sub Category Type',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectGoodsReceivedDonationSubCategory',
                  'options' => array(),
             ),
         ));

        $this->add(array(
           'name' => 'item_name_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Select Item Name',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectGoodsReceivedDonationItemName',
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

     private function createDonationItemCategoryType()
    {
        // You probably want to get those from the Database as in previous example
       // $dbAdapter = $this->adapter;
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, category_type FROM item_category';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['category_type'];
        }
        return $selectData;
    } 
 }
