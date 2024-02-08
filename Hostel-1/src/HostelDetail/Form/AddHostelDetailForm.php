<?php

namespace HostelDetail\Form;

use Zend\Form\Form;

class AddHostelDetailForm extends Form
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
				'type' => 'Text',
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
                                        'value_options' => array(
                                            '0' => '--Select--',
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'hostel_name',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'hostel_address',
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
				'name' => 'hostel_contact_no',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'provost_name',
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'provost_address',
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
				'name' => 'provost_contact_no',
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
                                'options' => array(
                                    'icon' => '<i class="icon icon-foo">',
                                    
                                ),
				'attributes' => array(
					'value' => 'Save',
					'id' => 'submitbutton',
                                        'class' => 'btn btn-success',
                                        
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