<?php

namespace ResearchPublication\Form;

use Zend\Form\Form;

class CollegePublication extends Form
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
				'name' => 'grant_application_type',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                    'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'Beginner Faculty Researcher (BFR)',
                                     '2' => 'Mid-Career Researcher (MCR)',
                                     '3' => 'Advanced Career Researcher (ACR)',
                                     '4' => 'Annual Grant as an Early Career Researcher (ECR)',
                                    
                                     
                                 ),
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                $this->add(array(
				'name' => 'research_title',
				'type' => 'Text',
				'options' => array(
					
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'lead_first_name',
				'type' => 'Text',
				'options' => array(
					'label' => 'First Name',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'lead_middle_name',
				'type' => 'Text',
				'options' => array(
					'label' => 'Middle Name',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'lead_last_name',
				'type' => 'Text',
				'options' => array(
					'label' => 'Last Name',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'lead_college',
				'type' => 'Select',
				'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'College of Natural Resources ',
                                     '2' => 'College of Science and Technology ',
                                     '3' => 'Gaeddu College of Business Studies ',
                                     '4' => 'College of Language and Culture Studies ',
                                     '5' => 'Jigme Namgyel Engineering College ',
                                     '6' => 'Paro College of Education ',
                                     '7' => 'Samtse College of Education  ',
                                     '8' => 'Sherubtse College  ',
                                    ),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                
                $this->add(array(
				'name' => 'lead_position',
				'type' => 'Select',
				'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'P5',
                                     '2' => 'P4',
                                     '3' => 'P3',
                                     '4' => 'P2',
                                     '5' => 'P1',
                                     '6' => 'EX/ES 3',
                                     '7' => 'EX/ES 2',
                                     '8' => 'EX/ES 1',
                    ),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'lead_sex',
				'type' => 'Text',
				'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'Male',
                                     '2' => 'Female',
                                     ),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'lead_email',
				'type' => 'Text',
				'options' => array(
					'label' => 'Email',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'lead_phone',
				'type' => 'Number',
				'options' => array(
					'label' => 'Phone',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'co_first_name',
				'type' => 'Text',
				'options' => array(
					'label' => 'First Name',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'co_middle_name',
				'type' => 'Text',
				'options' => array(
					'label' => 'Middle Name',
					),
				'attributes' => array (
					'class' => 'form-control col-md-7 col-xs-12', 
					),
				));
                $this->add(array(
				'name' => 'co_last_name',
				'type' => 'Text',
				'options' => array(
					'label' => 'Last Name',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'co_college',
				'type' => 'Select',
				'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'College of Natural Resources ',
                                     '2' => 'College of Science and Technology ',
                                     '3' => 'Gaeddu College of Business Studies ',
                                     '4' => 'College of Language and Culture Studies ',
                                     '5' => 'Jigme Namgyel Engineering College ',
                                     '6' => 'Paro College of Education ',
                                     '7' => 'Samtse College of Education  ',
                                     '8' => 'Sherubtse College  ',
                                    ),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'co_position',
				'type' => 'Select',
				'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'P5',
                                     '2' => 'P4',
                                     '3' => 'P3',
                                     '4' => 'P2',
                                     '5' => 'P1',
                                     '6' => 'EX/ES 3',
                                     '7' => 'EX/ES 2',
                                     '8' => 'EX/ES 1',
                                    ),
                    
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'co_sex',
				'type' => 'Text',
				'value_options' => array(
                                     '0' => '--select--',
                                     '1' => 'Male',
                                     '2' => 'Female',
                                 ),    
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'co_email',
				'type' => 'Text',
				'options' => array(
					'label' => 'Email',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'co_phone',
				'type' => 'Number',
				'options' => array(
					'label' => 'Phone',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		
		$this->add(array(
				'name' => 'pd_problem_statement',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Problem Statement',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'pd_research_question',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Research Questions and Sub-question',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'review_of_key_literatures',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Review of key Literatures',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'approach_theory',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Approach/ParadigmTheory',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'data_collection_prodecures',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Data Collection Procedures',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'data_analysis_prodecures',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Data Analysis Procedures',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'data_presentation',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Data Presentation',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'ethical_consideration',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Ethical Consideration',
					),
				'attributes' => array (
					'class' => 'form-control',
					),
				));
                $this->add(array(
				'name' => 'significancy_of_study',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Significancy of the Study',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'research_dissemination',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Research Dissemination (for example,publication in journals, periodicals,newspaper,etc )',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'reference',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Reference',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'action_plan_budget_details',
				'type' => 'TextArea',
				'options' => array(
					'label' => 'Particulars',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'start_date',
				'type' => 'Date',
				'options' => array(
					'label' => 'Start Date',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		
		$this->add(array(
				'name' => 'end_date',
				'type' => 'Date',
				'options' => array(
					'label' => 'End Date',
					),
				'attributes' => array (
					'class' => 'form-control', 
					),
				));
		$this->add(array(
				'name' => 'budget',
				'type' => 'Number',
				'attributes' => array (
					'placeholder' => 'Budget',
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'has_received_university',
				'type' => 'radio',
				'options' => array(
                                        'placeholder' => 'Has Principal researcher previously received AURG in past two years?',
					'class' => 'control-label',
                                        'value_options' => array(
                                            'yes' => array(
                                                'value' => '1',
                                                    ),
                                                'no' => array(
                                                'value' => '2',
                                                ),
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                $this->add(array(
				'name' => 'has_received_universit_amount',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                        'value_options' => array(
                                            '0' => '--select--',
                                            '1' => '2013',
                                            '2' => '2014',
                                            '3' => '2015',
                                            '4' => '2016',
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
                $this->add(array(
				'name' => 'has_received_college',
				'type' => 'radio',
				'options' => array(
                                        'placeholder' => 'Has Principal researcher previously received college research grant in past two years?',
					'class' => 'control-label',
                                        'value_options' => array(
                                            'yes' => array(
                                                'value' => '1',
                                                    ),
                                                'no' => array(
                                                'value' => '2',
                                                ),
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                $this->add(array(
				'name' => 'has_received_college_amount',
				'type' => 'Select',
				'options' => array(
					'class' => 'control-label',
                                        'value_options' => array(
                                            '0' => '--select--',
                                            '1' => '2013',
                                            '2' => '2014',
                                            '3' => '2015',
                                            '4' => '2016',
                                        ),
                                    
					),
                    'attributes' =>array(
                                        'class' => 'form-control',
                                        
                                ),
				));
                
		$this->add(array(
				'name' => 'college_ethic_committee_approve_no',
				'type' => 'Number',
				'attributes' => array (
					'placeholder' => 'Ethic Committee Approved Number',
					'class' => 'form-control', 
					),
				));
                $this->add(array(
				'name' => 'application_date',
				'type' => 'Date',
				'attributes' => array (
					'placeholder' => 'Application Date',
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