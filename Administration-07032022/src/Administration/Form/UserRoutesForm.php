<?php

namespace Administration\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class UserRoutesForm extends Form
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
           'name' => 'route_name',
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
           'name' => 'menu',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Menu Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
        				  'id' => 'selectMenuLevel',
        				  'options' => array(
        						'1' => 'Sub Menu Level 1',
        						'2' => 'Sub Menu Level 2',
        						'3' => 'Sub Menu Level 3',
        				  ),
             ),
         ));

         $this->add(array(
           'name' => 'parent_module',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Parent Module',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectParentModule',
                  'options' => array(),
                  'required' => 'required',
             ),
         ));
         
		 
		 $this->add(array(
           'name' => 'user_sub_menu_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Sub Menu',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'id' => 'selectSubMenuLevel',
				  'options' => array(),
                  'required' => 'required',
             ),
         ));
		            
         $this->add(array(
           'name' => 'route_remarks',
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
           'name' => 'route_category',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
                  'id' => 'selectModuleLevel',
                  'options' => $this->createModuleName(),
             ),
         ));
         
         $this->add(array(
           'name' => 'route_details',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Route',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'selectRouteDetails',
                  'options' => array(),
                  'required' => 'required',
             ),
         ));

         $this->add(array(
           'name' => 'user_menu_level',
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
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                    'timeout' => 1200
                )
             )
         ));

         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Route',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }


     private function createModuleName()
     {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT * FROM user_menu WHERE user_menu_level='0'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['menu_name'];
        }
        return $selectData;
     }
}