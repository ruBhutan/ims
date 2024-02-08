<?php

namespace GoodsTransaction\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class GoodsSearchForm extends Form
{
  protected $serviceLocator;
  
	public function __construct($serviceLocator = null, array $options = [])
	{

		// we want to ignore the name passed
        parent::__construct('ajax', $options);

        $this->serviceLocator = $serviceLocator;
        $this->ajax = $serviceLocator;
        $this->ajax = $options;
		
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));


		$this->add(array(
			'name' => 'category',
			'type' => 'Select',
			'options' => array(
				'empty_option' => 'Please Select Category',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
				),
			'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectGoodsReceivedDonationCategory',
                  'options' => $this->createItemCategory(),
                  'required' => 'required'
             ),
		));

		$this->add(array(
			'name' => 'sub_category',
			'type' => 'Select',
			'options' => array(
				'empty_option' => 'Please Select Sub Category',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
				),
			'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectGoodsReceivedDonationSubCategory',
                  'options' => array(),
                  'required' => 'required'
             ),
		));
		
		$this->add(array(
           'name' => 'item_name',
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
                
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));            
	}


	private function createItemCategory()
    {
        // You probably want to get those from the Database as in previous example
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
