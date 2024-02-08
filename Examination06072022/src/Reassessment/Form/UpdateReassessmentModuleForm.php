<?php
namespace Reassessment\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class UpdateReassessmentModuleForm extends Form
 {

    protected $reassessment_list_id;

     public function __construct($reassessment_list_id)
     {
        parent::__construct('updatereassessmentmodule');

        $this->reassessment_list_id = $reassessment_list_id;
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        foreach($this->reassessment_list_id as $id){ 
          $this->add(array(
			'name' => 'payment_remarks_'.$id,
			'type' => 'Textarea',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'rows' => 3,
				),
		));
        }

        foreach($this->reassessment_list_id as $id){ 
            $this->add(array(
              'name' => 'reassessment_remarks_'.$id,
              'type' => 'Textarea',
              'options' => array(
                  'class' => 'control-label',
                  ),
              'attributes' =>array(
                  'class' => 'form-control',
                  'rows' => 3,
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
