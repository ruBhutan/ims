<?php

namespace Review\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class StudentFeedbackForm extends Form
{
	protected $elementCount;
        protected $username;
	protected $organisation_id;
	protected $student_id;
	
	public function __construct($name = null, array $options = [])
        {
        /*parent::__construct('budgetproposal');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
		 */
        parent::__construct('ajax', $options);

       $this->adapter1 = $name; 
       $this->ajax = $name; 
       $this->ajax = $options;
         
         //the following are so that we can get the organisation id
        $user_session = new Container('user');
        $this->username = $user_session->username;
        $this->organisation_id = $this->getOrganisationId($this->username);
        $this->student_id = $this->getStudentId($this->username);
        $this->elementCount = $this->getElementCount();
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left radio',
        ));
		
		$this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
        
		$this->add(array(
             'name' => 'employee_details_id',
              'type' => 'Hidden'  
         ));
		
		$this->add(array(
             'name' => 'appraisal_period',
              'type' => 'Hidden'  
         ));
         
                $this->add(array(
            'name' => 'academic_module',
             'type'=> 'select',
              'options' => array(
                  'empty_option' => 'Please Select Academic Module',
                                  'disable_inarray_validator' => true,
                                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control',
                   'id' => 'feedbackModule',
                    'options' => $this->createAcademicModule(),
              ),
          ));
                $this->add(array(
            'name' => 'module_tutor',
             'type'=> 'select',
              'options' => array(
                  'empty_option' => 'Please Select Module Tutor',
                                  'disable_inarray_validator' => true,
                                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control',
                   'id' => 'feedbackModuleTutor',
                    'options' => array(),
              ),
          ));
		 
		
		for($i=1; $i <= $this->elementCount; $i++)
		{
			$this->add(array(
			   'name' => 'evaluation'.$i,
			   'type'=> 'Radio',
			   'options' => array(
                                    'class' => 'flat',
                                    'value_options' => array(
                                        '5' => 'Excellent  .',
                                        '4' => 'Very Good   .',
                                        '3' => 'Good   .',
                                        '2' => 'Poor   .',
                                        '1' => 'Very Poor   .',
                                    )
                            ),
			 ));
		}
		 
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
     
    private function getOrganisationId($username)
    {
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT `t1`.`organisation_id` AS `organisation_id` FROM `student` as `t1` WHERE t1.student_id = "'. $this->username.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        
        //set a default value
        $organisationId = 0;
       foreach ($result as $res) {
            $organisationId = $res['organisation_id'];
        }
        
        return $organisationId;
    }
	
    private function getStudentId($username)
    {
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT `t1`.`id` AS `id` FROM `student` as `t1` WHERE t1.student_id = "'. $this->username.'"';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        //set a default value
        $student_id = 0;
       foreach ($result as $res) {
            $student_id = $res['id'];
        }
        return $student_id;
    }
    
    private function getElementCount()
    {
        $dbAdapter1 = $this->adapter1;
        $sql       = 'SELECT * FROM `student_feedback_questions`';
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        
        foreach ($result as $res) {
            $question_nos[$res['id']] = $res['id'];
        }
        
        return count($question_nos);
    }
    
    private function createAcademicModule()
    {
        //need to get which part of the year so that we do not mix the enrollment years
        $present_month = date('m');
        //if((int)$present_month <= 6)
        //	$academic_year = date('Y')-1;
        //else 
                $academic_year = date('Y');
        $section = NULL;
        $programmes_id = NULL;
        $semester = NULL;
        $studentID = $this->student_id;
        
        $dbAdapter1 = $this->adapter1;
        $sql1 = 'SELECT `t1`.`student_id`, `t1`.`programmes_id`, `t2`.`student_section_id`, `t2`.`semester_id`, `t3`.`section` FROM `student`as `t1` '
                . 'INNER JOIN `student_semester_registration` as `t2` ON `t1`.`id`= `t2`.`student_id` '
                . 'INNER JOIN `student_section` as `t3` ON `t2`.`student_section_id` = `t3`.`id` WHERE `t1`.`id` ='.$studentID;
        
        $statement = $dbAdapter1->query($sql1);
        $result    = $statement->execute();
        
        foreach($result as $set){
            $section = $set['section'];
            $programmes_id = $set['programmes_id'];
            $semester = $set['semester_id'];
        }
        
        $sql2 = 'SELECT `t1`.`module_code`, `t2`.`academic_modules_id`, `t3`.`*` FROM `academic_modules`as `t1` '
                    . 'INNER JOIN `academic_modules_allocation` as `t2` ON `t1`.`id`= `t2`.`academic_modules_id` '
                    . 'INNER JOIN `academic_module_tutors` as `t3` ON `t2`.`id` = `t3`.`academic_modules_allocation_id` '
                    . 'WHERE `t2`.`programmes_id` = '.$programmes_id.' AND `t2`.`semester`="'.$semester.'" AND `t3`.`section` = "'.$section.'"';
        $statement2 = $dbAdapter1->query($sql2);
        $result2    = $statement2->execute();
        $selectData = array();

       foreach ($result2 as $res) {
            $selectData[$res['id']] = $res['module_code'];
        }
        return $selectData;
    }
}
