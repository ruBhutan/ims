<?php

namespace GuestHouseRoom\Form;

use Zend\Form\Form;

class AddGuestHouseRoomForm extends Form
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
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                        'value_options' => array(
                                            '0' => '--select',
                                        ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                $this->add(array(
				'name' => 'guest_house_floor_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                                                             
                                ),
				));
                $this->add(array(
				'name' => 'guest_house_room_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Room No'
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'guest_house_room_type',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'guest_house_room_charge',
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