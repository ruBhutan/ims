<?php

namespace BookGuestHouse\Form;

use Zend\Form\Form;

class BookGuestHouseRoomForm extends Form
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
				'name' => 'guest_check_in',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                                                             
                                ),
				));
                $this->add(array(
				'name' => 'guest_check_out',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'placeholder' => 'Room No'
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'guest_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                        
                                ),
				));
                
                 $this->add(array(
				'name' => 'guest_particular',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        'rows' => 5,
                                        
                                ),
				));
                             
                                             
                           
		              
	}
}