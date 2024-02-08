<?php

namespace EmpTravelAuthorization\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class SubmitApprovalForm extends Form
{
	public function __construct()
     {
        parent::__construct('submitapproval');
         
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
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));

        $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

        $this->add(array(
             'name' => 'remarks',
             'type'=>'Textarea',
                'options' => array(
                    'class' => 'control-label',
                    ),
                'attributes' => array(
                 'class' => 'form-control',
                 'rows' => 4,
                 
             ),
         ));

         $this->add(array(
             'name' => 'tour_status',
             'type'=>'Select',
             'options' => array(
                'class'=>'control-label',
                 'empty_option' => 'Select Tour Status',
                      'value_options' => array(
                        'Approved' => 'Approve',
                        'Rejected' => 'Reject',
                    ),
                ),
             'attributes' => array(
                 'class' => 'form-control',
                 'required' => true,
                 
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
					'value' => 'Submit',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
     }
}
