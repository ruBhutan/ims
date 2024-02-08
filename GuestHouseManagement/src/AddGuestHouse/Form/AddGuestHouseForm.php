<?php

namespace AddGuestHouse\Form;

use Zend\Form\Form;

class AddGuestHouseForm extends Form
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
				'name' => 'college_name',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                        'value_options' => array(
                                            '0' => '--select--',
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'guest_house_name',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                $this->add(array(
				'name' => 'guest_house_address',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        
                                ),
				));
                $this->add(array(
				'name' => 'guest_house_caretaker',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'guest_house_caretaker_add',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'rows' =>3,
                                        
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