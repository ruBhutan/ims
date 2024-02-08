<?php

namespace EmpTransfer\Form;

use Zend\Form\Form;

class TransferForm extends Form
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
				'name' => 'emp_id',
				'type' => 'Select',
                              'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                      '2' => 'RUB100', 
                     '3' => 'RUB200',                
                                        ),
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                  
                                ),
                    
				));
                
                $this->add(array(
				'name' => 'transfer_order_no',
				'type' => 'Text',
                              
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
                    
				));
                $this->add(array(
				'name' => 'transfer_order_date',
				'type' => 'Date',
                                'options' => array(
                                        'class' => 'control-label',
                                ),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                $this->add(array(
				'name' => 'joining_date_this_agency',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                  
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));      
              
                $this->add(array(
				'name' => 'joining_date_new_agency',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                   	),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                $this->add(array(
				'name' => 'working_agency',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                                        '2' => 'Office of the Vice Chancellor',
                                        '3' => 'Sherubtse College',
                                        '4' => 'Gedu College',
                                        ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                 $this->add(array(
				'name' => 'major_occupational_group',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                                        '2' => 'Academic Service Group',
                                        '3' => 'Administrative & Technical Category',
                                        ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                 
                 $this->add(array(
				'name' => 'sub_occupational_group',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                     'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'position_category',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                                        '2' => 'Academic Category',
                                        '3' => 'Executive Category',
                                        '4' => 'Operational Category',
                                        '5' => 'Professional Category',
                                        '6' => 'Supervisory and Support Category',
                                       
                                        ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                  $this->add(array(
				'name' => 'position_title',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'position_level',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                 $this->add(array(
				'name' => 'pay_scale',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                   $this->add(array(
				'name' => 'transfer_reasons',
				'type' => 'TextArea',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                   
                    $this->add(array(
				'name' => 'joining_date_this_department',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                  
					),
                                'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));      
              
                $this->add(array(
				'name' => 'joining_date_new_department',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                   	),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                ),
				));
                $this->add(array(
				'name' => 'department',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',
                                        '2' => 'Office of the Vice Chancellor',
                                        '3' => 'Sherubtse College',
                                        '4' => 'Gedu College',
                                        ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                 
                  $this->add(array(
				'name' => 'upload',
				'type' => 'File',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                   $this->add(array(
				'name' => 'date_of_extension',
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                     '1' => '--select--',),
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
					'value' => 'Reset',
					'id' => 'submitbutton',
                                        'class' => 'btn btn-default'
					),
				));
              
	}
}