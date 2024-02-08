<?php
namespace RecheckMarks\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class UpdateRecheckMarksForm extends Form
 {

    protected $recheck_list_id;

     public function __construct($recheck_list_id)
     {
        parent::__construct('updaterecheckmarks');

        $this->recheck_list_id = $recheck_list_id;
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));


        foreach($this->recheck_list_id as $id){ 
            $this->add(array(
              'name' => 'recheck_remarks_'.$id,
              'type'=> 'Select',
			 'options' => array(
				//'empty_option' => 'Please Select',
				'disable_inarray_validator' => true,
				'class'=>'control-label',
				'value_options'=> array(
                        '0' => 'Please Select',
						'Change' => 'Change',
                        'No change' => 'No change'
				),
			 ),
			 'attributes' => array(
				  'class' => 'form-control'
			 ),
		 ));
          }
        

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
                    'value' => 'Update Status',
                    'id' => 'submitbutton',
                    'class' => 'btn btn-success',
                    ),
                
                ));
     }
 }
