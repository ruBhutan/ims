<?php

namespace ParentDashboard\Form;

use Zend\Form\Form;

class ParentDashboardForm extends Form
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
              'type' => 'Hidden'  
         ));
         
          $this->add(array(
           'name' => 'account_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                 
             ),
         ));

         
         
    
         $this->add(array(
				'name' => 'save',
				'type' => 'Submit',
				'attributes' => array(
                                    'class'=>'control-label',
					'value' => 'Save',
					'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
					),
				
				));
     }
}



