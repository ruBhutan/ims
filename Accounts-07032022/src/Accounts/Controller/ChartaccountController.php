<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class ChartaccountController extends AbstractActionController
{   
	protected $_table; 		// database table 

	/**
	 * Zend Default TableGateway
	 * Table name as the parameter
	 * returns obj
	 */
	public function getDefaultTable($table)
	{
		$this->_table = new TableGateway($table, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
		return $this->_table;
	}

   /**
    * User defined Model
    * Table name as the parameter
    * returns obj
    */
    public function getDefinedTable($table)
    {
    	$sm = $this->getServiceLocator();
    	$this->_table = $sm->get($table);
    	return $this->_table;
    }
    /**
     * initial set up
     * general variables are defined here
     */
    public function init()
	{				
		$user_session = new Container('user');
        $this->username = $user_session->username;
		
		$empData = $this->getDefinedTable('Accounts\EmployeeDetailsTable')->getUserDetails($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
		}
		
		$organisationID = $this->getDefinedTable('Accounts\EmployeeDetailsTable')->getUserDetails($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		$this->_id = $this->params()->fromRoute('id');

		$this->_created = date('Y-m-d H:i:s');
		$this->_modified = date('Y-m-d H:i:s');
	}	
	/**
	 *  index action
	 */
	public function indexAction()
	{
		$this->init();
		return new ViewModel(array(
				'title' => 'Chart of Account',
				'classes' => $this->getDefinedTable('Accounts\ClassTable')->getAll(),
				'groupObj' => $this->getDefinedTable('Accounts\GroupTable'),
				'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
				'subheadObj' => $this->getDefinedTable('Accounts\SubheadTable'),
		));
	}
	/**
	 *  class action
	 */
	public function classAction()
	{
		$this->init();
		return new ViewModel(array(
			'title' => 'Class',
			'class' => $this->getDefinedTable('Accounts\ClassTable')->getAll(),
		));		
	} 
	/**
	 *  function/action to add class
	 */
	public function addclassAction()
	{
		$this->init();
	
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
					'code' => $form['code'],
					'name' => $form['name'],
					'author' =>$this->employee_details_id ,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\ClassTable')->save($data);
	
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ New Class successfully added");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to add new Class");
			endif;
			return $this->redirect()->toRoute('chartaccount');
		}
		return  new ViewModel(array(
		));
	}
	/**
	 *  function/action to edit class
	 */
	public function editclassAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
					'id' => $this->_id,
					'code' => $form['code'],
					'name' => $form['name'],
					'author' =>$this->employee_details_id ,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\ClassTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ Class successfully updated");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to update Class");
			endif;
			return $this->redirect()->toRoute('chartaccount');
		}
		return new ViewModel(array(
				'class' => $this->getDefinedTable('Accounts\ClassTable')->get($this->_id),
		));
	}
	/**
	 *  group action
	 */
	public function groupAction()
	{
		$this->init();
		return new ViewModel(array(
			'title' => 'Group',
			'class' => $this->getDefinedTable('Accounts\ClassTable')->getAll(),
			'groups' => $this->getDefinedTable('Accounts\GroupTable')->getAll(),
		));
	} 
	/**
	 *  function/action to add group
	 */
	public function addgroupAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
					'code' => $form['code'],
					'name' => $form['name'],
					'class' => $form['class'],
					'author' =>$this->employee_details_id ,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\GroupTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ New Group successfully added");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to add new Group");
			endif;
			return $this->redirect()->toRoute('chartaccount', array('action'=>'group'));
		}
		return new ViewModel(array(
			'class' => $this->getDefinedTable('Accounts\ClassTable')->getAll(),
		));
	}
	/**
	 *  function/action to edit group
	 */
	public function editgroupAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
					'id' => $this->_id,
					'code' => $form['code'],
					'name' => $form['name'],
					'class' => $form['class'],
					'author' =>$this->employee_details_id ,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\GroupTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ Group successfully updated");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to update Group");
			endif;
			return $this->redirect()->toRoute('chartaccount', array('action'=>'group'));
		}
		return new ViewModel(array(
				'group' => $this->getDefinedTable('Accounts\GroupTable')->get($this->_id),
				'class' => $this->getDefinedTable('Accounts\ClassTable')->getAll(),
		));
	}
	/**
	 *  head action
	 */
	public function headAction()
	{
		$this->init();
		return new ViewModel(array(
			'title' => 'Head',
			'head' => $this->getDefinedTable('Accounts\HeadTable')->getAll(),			
		));
	}
	/**
	 *  function/action to add head
	 **/
	public function addheadAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
					'code' => $form['code'],
					'name' => $form['name'],
					'group' => $form['group'],
					'author' =>$this->employee_details_id ,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\HeadTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ New Head successfully added");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to add new Head");
			endif;
			return $this->redirect()->toRoute('chartaccount', array('action'=>'head'));
		}
		return new ViewModel(array(
			'group' => $this->getDefinedTable('Accounts\GroupTable')->getAll(),
		));
	}
	/**
	 *   function/action to edit head
	 **/
	public function editheadAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
					'id' => $this->_id,
					'code' => $form['code'],
					'name' => $form['name'],
					'group' => $form['group'],
					'author' =>$this->employee_details_id ,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\HeadTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ Head successfully updated");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to update Head");
			endif;
			return $this->redirect()->toRoute('chartaccount', array('action'=>'head'));
		}
		return new ViewModel(array(
			'head' => $this->getDefinedTable('Accounts\HeadTable')->get($this->_id),
			'group' => $this->getDefinedTable('Accounts\GroupTable')->getAll(),
		));
	}
	/**
	 *  subhead action
	 */
	public function subheadAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
			$class = $form['class'];
			$group = $form['group'];
			$head = $form['head'];
		else:
			$class = '-1';
			$group = '-1';
			$head = '-1';
		endif;
		$data = array(
			'class' => $class,
			'group' => $group,
			'head' => $head,
		);
		$subheads = $this->getDefinedTable('Accounts\SubheadTable')->getSubhead($data);
		return new ViewModel(array(
				'title' => 'SubHead',
				'data' => $data,
				'classObj' => $this->getDefinedTable('Accounts\ClassTable'),
				'groupObj' => $this->getDefinedTable('Accounts\GroupTable'),
				'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
				'subheads' => $subheads,
		));
	}
	/**
	 * get group by class
	**/
	public function getgroupAction()
	{
		$this->init();
		$form = $this->getRequest()->getPost();
		
		$class_id = $form['class'];
		$groups = $this->getDefinedTable('Accounts\GroupTable')->get(array('class' => $class_id));
		$grp.="<option value='-1'>All</option>";
		foreach($groups as $group):
			$grp.= "<option value='".$group['id']."'>".$group['code']."</option>";
		endforeach;
		
		$hd.="<option value='-1'>All</option>";
		
		echo json_encode(array(
				'group' => $grp,
		));
		exit;
	}
	/**
	 * get group by class
	**/
	public function getheadAction()
	{
		$this->init();
		$form = $this->getRequest()->getPost();
		
		$group_id = $form['group'];
		$heads = $this->getDefinedTable('Accounts\HeadTable')->get(array('group' => $group_id));
		
		$hd.="<option value='-1'>All</option>";
		foreach($heads as $head):
			$hd.= "<option value='".$head['id']."'>".$head['code']."</option>";
		endforeach;
		
		echo json_encode(array(
				'head' => $hd,
		));
		exit;
	}
	/**
	 *  function/action to add subhead
	 **/
	public function addsubheadAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'head' => $form['head'],
				'code' => $form['shcode'],
				'name' => $form['shname'],
				'author' =>$this->employee_details_id ,
				'created' =>$this->_created,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\subheadTable')->save($data);
			if($result > 0):
				$master_details_data = array(
					'Sub_head' =>$result,
					'type' =>9,
					'ref_id' =>$result,
					'code' => $form['shcode'],
					'name' => $form['shname'],
					'author' =>$this->employee_details_id ,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
				);
				$MD_result = $this->getDefinedTable('Accounts\MasterDetailsTable')->save($master_details_data);
				if($MD_result > 0):
					$this->getDefinedTable('Accounts\MasterDetailsTable')->save(array('id'=>$MD_result,'ref_id'=>$MD_result));
					$this->flashMessenger()->addMessage("success^ New Subhead successfully added");
				else:
					$this->flashMessenger()->addMessage("Failed^ Failed to update details");
				endif;	
            else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new Subhead");
            endif; 			
		return $this->redirect()->toRoute('chartaccount', array('action'=>'subhead'));
		}
		return new ViewModel(array(
				'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
		));
	}
		
	/**
	 *  function/action to edit subhead
	 **/
	public function editsubheadAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
		$form = $this->getRequest()->getPost();
			$data = array(
				'id'   => $this->_id,
				'head' => $form['head'],
				'code' => $form['shcode'],
				'name' => $form['shname'],
				'author' =>$this->employee_details_id ,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\SubHeadTable')->save($data);
			if($result > 0):
				$master_details_data = array(
					'id'     =>$this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn(array('sub_head'=>$form['subhead_id']), $column='id'),
					'Sub_head' =>$form['subhead_id'],
					'type' =>9,
					'ref_id' =>$result,
					'code' => $form['shcode'],
					'name' => $form['shname'],
					'author' =>$this->employee_details_id ,
					'modified' =>$this->_modified,
				);
				$MD_result = $this->getDefinedTable('Accounts\MasterDetailsTable')->save($master_details_data);
				if($MD_result > 0):
					$this->getDefinedTable('Accounts\MasterDetailsTable')->save(array('id'=>$MD_result,'ref_id'=>$MD_result));
					$this->flashMessenger()->addMessage("success^ New Subhead successfully added");
				else:
					$this->flashMessenger()->addMessage("Failed^ Failed to update details");
				endif;	
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new Subhead");
			endif; 		
			return $this->redirect()->toRoute('chartaccount', array('action'=>'subhead'));
		}
		return new ViewModel(array(
			'title' => 'Edit Sub Head',
			'subheads' => $this->getDefinedTable('Accounts\SubHeadTable')->get($this->_id),
			'headObj' =>$this->getDefinedTable('Accounts\HeadTable'),
		));
	}
	
    /**
     *  journal action
     */
    public function journalAction()
    {
        $this->init();
        return new ViewModel(array(
            'title' => 'Journal',
            'journal' => $this->getDefinedTable('Accounts\JournalTable')->getAll(),
        ));
    }  
     /**
     *  function/action to add Party Role
     */
     public function addjournalAction()
    {
       	$this->init();
        if($this->getRequest()->isPost()){
            $form = $this->getRequest()->getPost();
            $data = array( 
                    'code' => $form['code'],
                    'journal' => $form['journal'],
                    'prefix' => $form['prefix'],
                    'author' =>$this->employee_details_id ,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
            );
            $result = $this->getDefinedTable('Accounts\JournalTable')->save($data);
            if($result > 0):
                $this->flashMessenger()->addMessage("success^ New Journal successfully added");
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to add new Journal");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action'=>'journal'));             
        }
        return new ViewModel(array(
        ));               
    }
     /**
     * edit journal action
     **/
    public function editjournalAction()
    {
       $this->init();
        if($this->getRequest()->isPost())
        {
            $form=$this->getRequest()->getPost();
            $data=array(
                 'id' => $this->_id,
                'code' => $form['code'],
                'journal' => $form['journal'],
                'prefix' => $form['prefix'],
                'author' =>$this->employee_details_id ,
				'modified' =>$this->_modified,
            );
            $result = $this->getDefinedTable('Accounts\JournalTable')->save($data);
            if($result > 0):
                $this->flashMessenger()->addMessage("success^ Journal successfully updated");
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to update Journal");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action'=>'journal')); 
        }
        return new ViewModel(array(
        	'journal' => $this->getDefinedTable('Accounts\JournalTable')->get($this->_id),
        ));             
    }
}
