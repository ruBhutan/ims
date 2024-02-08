<?php

namespace HostelDetail\Form;

use Zend\Form\Form;

class AddHostelCategoryForm extends Form
{
	public function __construct()
	{
		 // we want to ignore the name passed
		parent::__construct();
                
                $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));
		
		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
				));
                
                $this->add(array(
				'name' => 'hostel_type',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                        'value_options' => array(
                                            '0' => '--Select--',
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));  
                
                                $this->add(array(
				'name' => 'hostel_category',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                        
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));

                                                      
                                                                     
                              
		$this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Save',
					'id' => 'submitbutton',
                                        'class' => 'btn btn-success'
					),
				));
                $this->add(array(
				'name' => 'reset',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Cancel',
					'id' => 'resetbutton',
                                        'class' => 'btn btn-warning'
					),
				));
                
	}
}