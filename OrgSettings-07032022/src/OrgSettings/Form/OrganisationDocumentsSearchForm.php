<?php
namespace OrgSettings\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class OrganisationDocumentsSearchForm extends Form
 {
     public function __construct()
     {
        parent::__construct('organisationdocumentssearch');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
         $this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));
                
        $this->add(array(
			'name' => 'document_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Type',
				 'disable_inarray_validator' => true,
                 'class'=>'control-label',
                 'value_options' => array(
                    'Logo' => 'Logo',
                    'Banner' => 'Banner'
               ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));
     }
 }