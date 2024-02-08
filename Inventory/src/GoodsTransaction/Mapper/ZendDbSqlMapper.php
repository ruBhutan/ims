<?php

namespace GoodsTransaction\Mapper;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\ItemCategory;
use GoodsTransaction\Model\ItemSubCategory;
use GoodsTransaction\Model\ItemQuantityType;
use GoodsTransaction\Model\ItemName;
use GoodsTransaction\Model\ItemSupplier;
use GoodsTransaction\Model\ItemDonor;
use GoodsTransaction\Model\GoodsReceived;
use GoodsTransaction\Model\Itemreceivedpurchased;
use GoodsTransaction\Model\IssueGoods;
use GoodsTransaction\Model\DeptGoods;
use GoodsTransaction\Model\RequisitionIssueGoods;
use GoodsTransaction\Model\DeptIssueGoods;
use GoodsTransaction\Model\GoodsSurrender;
use GoodsTransaction\Model\GoodsTransfer;
use GoodsTransaction\Model\NominateSubStore;
use GoodsTransaction\Model\DeptGoodsSurrender;
use GoodsTransaction\Model\OrgGoodsTransfer;
use GoodsTransaction\Model\DisposeGoods;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Group;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements GoodsTransactionMapperInterface
{
	/**
	* @var \Zend\Db\Adapter\AdapterInterface
	*
	*/
	
	protected $dbAdapter;
	
	/*
	 * @var \Zend\Stdlib\Hydrator\HydratorInterface
	*/
	protected $hydrator;
	
	/*
	 * @var \GoodsTransaction\Model\GoodsTransactionInterface
	*/
	protected $goodsTransactionPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			//\stdClass $goodsTransactionPrototype
			GoodsTransaction $goodsTransactionPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->goodsTransactionPrototype = $goodsTransactionPrototype;
	}


	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$emp_id));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$username));
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	/*
	* Get organisation id based on the username
	*/
	
	public function getDepartmentId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$username));
		$select->columns(array('departments_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getDepartmentUnitId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$username));
		$select->columns(array('departments_units_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getUserDetails($username, $usertype)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$name = $set['first_name']." ".$set['middle_name']." ".$set['last_name'];
		}
		
		return $name;
	}

	public function getUserImage($username, $usertype)
	{
		$img_location = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
		}		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$img_location = $set['profile_picture'];
		}
		
		return $img_location;
	}

	/**
	* Find all employees in an organisation
	*/
	public function findAllEmployees($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id'))
				->where('t1.organisation_id = ' .$organisation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	/**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	
	public function findCategory($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('item_category');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Category with given ID: ($id) not found");
	}
	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllCategory()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'item_category'))
            	   ->join(array('t2' => 'item_major_class'),
            	   		't2.id = t1.major_class_id', array('major_class')); // join expression

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
	}
        
    /**
    * 
    * @param type $id
    * 
    * to find the Item Category for a given $id
    */
    public function findCategoryDetails($id) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'item_category'));
       
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }
            
            return array();
    }

    public function crossCheckItemCategory($itemType, $majorClass)
    {
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_category'));
        $select->where(array('t1.category_type' => $itemType, 't1.major_class_id' => $majorClass));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $itemCategory = 0;
        foreach($resultSet as $set){
            $itemCategory = $set['category_type'];
        }
        return $itemCategory;
    }            
        
    /**
	 * 
	 * @param type $ItemCategory
	 * 
	 * to save Item Category
	 */

    public function saveItemCategory(ItemCategory $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);

		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_category');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('item_category');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


   /**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/

	public function findSubCategory($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'item_sub_category'));
        $select->where(array('t1.id = ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Sub Category with given ID: ($id) not found");
	}
	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllSubCategory($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_sub_category')) //base table
        	   ->join(array('t2' => 'item_category'),
					   't2.id = t1.item_category_id', array('category_type'))
			   ->join(array('t3' => 'item_major_class'),
						't3.id = t2.major_class_id', array('major_class'));
        $select->where(array('t1.organisation_id = ?' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/**
         * 
         * @param type $id
         * 
         * to find Item Sub Category Details with given $id
         */
        public function findSubCategoryDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'item_sub_category')) // base table
            	   ->join(array('t2' => 'item_category'),
            			't2.id = t1.item_category_id', array('category_type'))
                    ->where(array('t1.id = ?' => $id)); 
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            return array();
        }


        public function crossCheckItemSubCategory($subCategoryType, $categoryType, $organisation_id)
        {
        	$sql = new Sql($this->dbAdapter);
	        $select = $sql->select();

	        $select->from(array('t1' => 'item_sub_category'));
	        $select->where(array('t1.sub_category_type' => $subCategoryType, 't1.item_category_id' => $categoryType, 't1.organisation_id' => $organisation_id));
	            
	        $stmt = $sql->prepareStatementForSqlObject($select);
	        $result = $stmt->execute();
	        
	        $resultSet = new ResultSet();
	        $resultSet->initialize($result);
	        $subCategoryType = 0;
	        foreach($resultSet as $set){
	            $subCategoryType = $set['sub_category_type'];
	        }
	        return $subCategoryType;
        }


    /**
	 * 
	 * @param type $ItemSubCategory
	 * 
	 * to save Item Sub Category
	 */

	public function saveItemSubCategory(ItemSubCategory $goodsTransactionObject, $item_category_id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['category_Type']);
		
		$goodsTransactionData['item_Category_Id'] = $item_category_id;
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_sub_category');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('item_sub_category');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	 /**
	 * 
	 * @param type $ItemSubCategory
	 * 
	 * to Delete Item Sub Category
	 */

	public function deleteItemSubCategory(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('item_sub_category');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}



	public function findItemQuantityType($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('item_quantity_type');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Quantity Type with given ID: ($id) not found");
	}
	
	/**
	* @return array/ItemQuantityType()
	*/
	public function findAllItemQuantityType($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_quantity_type'));  //join expression
        $select->where(array('t1.organisation_id = ?' => $organisation_id))
        	   ->order('id DESC');


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}

/**
    * 
    * @param type $id
    * 
    * to find the Item Quantity Type for a given $id
    */
    public function findItemQuantityTypeDetails($id) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'item_quantity_type'));
        $select->where(array('id = ? ' => $id));       
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Quantity Type with given ID: ($id) not found");
	}

	public function crossCheckItemQuantityType($quantityType, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_quantity_type'));
        $select->where(array('t1.item_quantity_type' => $quantityType, 't1.organisation_id' => $organisation_id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $itemQuantityType = 0;
        foreach($resultSet as $set){
            $itemQuantityType = $set['item_quantity_type'];
        }
        return $itemQuantityType;		
	}

	 /**
	 * 
	 * @param type $ItemQuantityType
	 * 
	 * to save Item Quantity Type
	 */

	public function saveItemQuantityType(ItemQuantityType $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_quantity_type');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('item_quantity_type');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	/**
	 * 
	 * @param type $ItemQuantityType
	 * 
	 * to Delete Item Quantity TYpe
	 */

	public function deleteItemQuantityType(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('item_quantity_type');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}
 


	public function findItemName($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('item_name');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Name with given ID: ($id) not found");
	}
	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllItemName($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_name')) //base table
               ->join(array('t2' => 'item_sub_category'), // join table with alias
                    't2.id = t1.item_sub_category_id', array('sub_category_type'))
               ->join(array('t3' => 'item_category'),
               		't3.id = t2.item_category_id', array('category_type'))
                ->join(array('t4' => 'item_quantity_type'), //join tabel with alias
                      't4.id = t1.item_quantity_type_id', array('item_quantity_type'));  //join expression
        $select->where(array('t2.organisation_id = ?' => $organisation_id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
         * 
         * @param type $id
         * 
         * to find Item Name with given $id
         */
        public function findItemNameDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
	        $select = $sql->select();
	        $select->from(array('t1' => 'item_name'))
	        	   ->join(array('t2' => 'item_sub_category'), // join table with alias
                    't2.id = t1.item_sub_category_id', array('sub_category_type', 'organisation_id'))
               	   ->join(array('t3' => 'item_category'),
               		't3.id = t2.item_category_id', array('category_type'))
                   ->join(array('t4' => 'item_quantity_type'), //join tabel with alias
                      't4.id = t1.item_quantity_type_id', array('item_quantity_type', 'organisation_id'))
	               ->where(array('t1.id = ? ' => $id));     
	            
	        $stmt = $sql->prepareStatementForSqlObject($select);
	        $result = $stmt->execute();  

	        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
	                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
	            }

	            throw new \InvalidArgumentException("Item Name with given ID: ($id) not found");
        }


        public function crossCheckItemName($itemName, $item_sub_category_id, $organisation_id)
        {
        	$itemSubCategory = $item_sub_category_id;

        	$sql = new Sql($this->dbAdapter);
	        $select = $sql->select();

	        $select->from(array('t1' => 'item_name'))
	        	   ->join(array('t2' => 'item_sub_category'),
	        			't2.id = t1.item_sub_category_id', array('sub_category_type', 'organisation_id'));
	        $select->where(array('t1.item_name' => $itemName, 't1.item_sub_category_id' => $itemSubCategory ,'t2.organisation_id' => $organisation_id));
	            
	        $stmt = $sql->prepareStatementForSqlObject($select);
	        $result = $stmt->execute();
	        
	        $resultSet = new ResultSet();
	        $resultSet->initialize($result);
	        $itemname = 0;
	        foreach($resultSet as $set){
	            $itemname = $set['item_name'];
	        }
	        return $itemname;
        }


	 /**
	 * 
	 * @param type $ItemName
	 * 
	 * to save Item Name
	 */

	public function saveItemName(ItemName $goodsTransactionObject, $item_category_id, $item_sub_category_id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);		

		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['category_Type']);
		unset($goodsTransactionData['item_Category_Id']);

		$organisation_id = $goodsTransactionData['organisation_Id'];
		//$item_sub_category_id = $goodsTransactionData['item_Sub_Category_Id'];
		unset($goodsTransactionData['organisation_Id']);

       // $goodsTransactionData['item_Category_Id'] = $item_category_id;

        //get the id of the item name
		$goodsTransactionData['item_Sub_Category_Id'] = $item_sub_category_id;
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_name');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('item_name');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}

	/**
	 * 
	 * @param type $ItemQuantityType
	 * 
	 * to Delete Item Quantity TYpe
	 */

	public function deleteItemName(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('item_name');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	/**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	
	public function findItemSupplier($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('supplier_details');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Supplier with given ID: ($id) not found");
	}
	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllItemSupplier($tableName, $organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)); // join expression
            $select->where(array('organisation_id' =>$organisation_id, 't1.supplier_status' => 'Active'));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllBlackListedSupplier($tableName, $organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName)) // join expression
               ->join(array('t2' => 'blacklisted_supplier_details'),
           			't1.id = t2.supplier_details_id', array('from_date', 'to_date', 'supporting_documents', 'supplier_details_id'));
        $select->where(array('t1.organisation_id' =>$organisation_id, 't1.supplier_status' => 'Inactive'));
      //  $select->order('t2.supplier_details_id DESC');

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/**
	* to find the file name to download
	*/
	public function getFileName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'supplier_details'))
			   ->join(array('t2' => 'blacklisted_supplier_details'),
					't1.id = t2.supplier_details_id', array('id', 'supporting_documents'))
			   ->where(array('t2.supplier_details_id = ?' => $id));
		//$select->columns(array('supporting_documents'));
		 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$fileLocation;
		foreach($resultSet as $set)
		{
			$fileLocation = $set['supporting_documents'];
		}
		
		return $fileLocation;
	}
        
    /**
    * 
    * @param type $id
    * 
    * to find the Item Supplier for a given $id
    */
    public function findItemSupplierDetails($id) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'supplier_details'))
        	   ->where('t1.id = ' .$id);
       
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }
            
            return array();
    }


    public function activateBlackListedSupplier($status, $previousStatus, $id)
    {
    	$goodsTransactionData['supplier_Status'] = $status;

        $action = new Update('supplier_details');
        $action->set($goodsTransactionData);
        if($previousStatus != NULL){
            $action->where(array('supplier_status = ?' => $previousStatus));
        } elseif($id != NULL){
            $action->where(array('id = ?' => $id));
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return;
    }


    /**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	
	public function findBlackListedSupplierDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'supplier_details'))
			   ->join(array('t2' => 'blacklisted_supplier_details'),
					't1.id = t2.supplier_details_id', array('from_date', 'to_date'));
		$select->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		}

		throw new \InvalidArgumentException("Black Listed Supplier with given ID: ($id) not found");
	}

    public function findGoodsSupplied($id)
    {
    	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
        $select->from(array('t1' => 'item_received_purchased'))
               ->join(array('t2' => 'supplier_details'),
                    't2.id = t1.supplier_details_id', array('supplier_name'));
        
		$select->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		}
		throw new \InvalidArgumentException("Supplier with given ID: ($id) not found");
    }


    public function findAllAddedSuppliedGoods($tableName, $status, $organisation_id, $id)
    {
    	$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)) // join expression
                   ->join(array('t2' => 'item_received_purchased'),
               			't2.id = t1.item_received_purchased_id', array('supplier_details_id'))
                   ->join(array('t3' => 'supplier_details'),
               			't3.id = t2.supplier_details_id', array('supplier_name'))
                   ->join(array('t4' => 'item_name'),
               			't4.id = t1.item_name_id', array('item_name', 'item_quantity_type_id', 'item_sub_category_id'))
                   ->join(array('t5' => 'item_sub_category'),
               			't5.id = t4.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
               			't6.id = t5.item_category_id', array('category_type'))
                   ->join(array('t7' => 'item_quantity_type'),
               			't7.id = t4.item_quantity_type_id', array('item_quantity_type'));
            $select->where(array('t2.organisation_id = ?' => $organisation_id, 't1.item_status = ?' => $status, 't1.item_received_purchased_id = ?' => $id, 't2.receipt_voucher_no IS NULL'));
            	 //  ->group(array('t1.item_received_purchased_id'));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }
            return array();
    }


    public function findAddGoodsSupplied($id)
    {

		$goods_supplied_id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		 $select->from(array('t1' => 'goods_received'))
        	   ->join(array('t2' => 'item_name'),
        			't2.id = t1.item_name_id', array('item_name'));
        $select->where(array('t1.id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        $resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$goods_supplied_id = $set['item_received_purchased_id'];
		}
		
		return $goods_supplied_id;
    }


    public function updateAddGoodsSupplied($status, $previousStatus, $id)
    {
    	//need to get the organisaiton id
		//$organisation_id = 1;
		$goodsTransactionData['item_status'] = $status;
		$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('item_status = ?' => $previousStatus));
		$action->where(array('item_received_purchased_id = ?' => $id));
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
    }

    /**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to Delete Add Goods Supplied
	 */

	public function deleteAddGoodsSupplied($id)
	{
		$action = new Delete('goods_received');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}

	public function crossCheckItemSupplier($supplierName, $supplierLicense, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'supplier_details'));
        $select->where(array('t1.supplier_license_no' => $supplierLicense, 't1.organisation_id' => $organisation_id));
        $select->where->like('t1.supplier_name', $supplierName);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $itemSupplier = 0;
        foreach($resultSet as $set){
            $itemSupplier = $set['supplier_name'];
        }
        return $itemSupplier;  
	}

	/**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to save Item Supplier
	 */

	public function saveItemSupplier(ItemSupplier $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['from_Date']);
		unset($goodsTransactionData['to_Date']);
		unset($goodsTransactionData['supporting_Documents']);
		unset($goodsTransactionData['supplier_Details_Id']);
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('supplier_details');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('supplier_details');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	/**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to save Item Supplier
	 */

	public function saveBlackListedSupplier(ItemSupplier $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		$goodsTransactionDataSample = $goodsTransactionData;
		unset($goodsTransactionData['id']);

		//defining an array and extracting elements of the goods Transaction model
		$blacklistedSupplierData = array();
		$blacklistedSupplierFields = array(
			'from_Date',
			'to_Date',
			'supporting_Documents',
			'supplier_Details_Id'
		);

		foreach ($goodsTransactionDataSample as $key => $value) {
			if(in_array($key, $blacklistedSupplierFields))
			{
				$blacklistedSupplierData = array_merge($blacklistedSupplierData, array($key=>$value));
				unset($goodsTransactionData[$key]);
			}
		}


		$blacklistedSupplierData['from_Date'] = date("Y-m-d", strtotime(substr($blacklistedSupplierData['from_Date'],0,10)));
		$blacklistedSupplierData['to_Date'] = date("Y-m-d", strtotime(substr($blacklistedSupplierData['to_Date'],0,10)));

		//need to get the file locations and store them in database
		$supporting_documents = $blacklistedSupplierData['supporting_Documents'];
		$blacklistedSupplierData['supporting_Documents'] = $supporting_documents['tmp_name'];

		//ID present, so it is an update
		$action = new Update('supplier_details');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		//If supplier status is Inactive then it will get the certain data and insert into blacklisted_supplier_details table
		if($goodsTransactionData['supplier_Status'] == 'Inactive'){

			$update_action = new Insert('blacklisted_supplier_details');
			$update_action->values(array(
				'from_date' => $blacklistedSupplierData['from_Date'],
				'to_date' => $blacklistedSupplierData['to_Date'],
				'supporting_documents' => $blacklistedSupplierData['supporting_Documents'],
				'supplier_details_id' => $blacklistedSupplierData['supplier_Details_Id'],
			));

			$sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($update_action);
            $result = $stmt->execute();
		}
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}

	/**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to Delete Item Supplier
	 */

	public function deleteItemSupplier(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('supplier_details');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	/**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	
	public function findItemDonor($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('item_donor_details');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Donar with given ID: ($id) not found");
	}
	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllItemDonor($tableName, $organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)); // join expression
            $select->where(array('organisation_id' =>$organisation_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
	}
        
    /**
    * 
    * @param type $id
    * 
    * to find the Item Donar for a given $id
    */
    public function findItemDonorDetails($id) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'item_donor_details'));
       
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }
            
            return array();
    }


    public function crossCheckItemDonor($donorName, $organisation_id)
    {
    	$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_donor_details'))
               ->columns(array('donor_name'));
        $select->where(array('t1.donor_name' => $donorName, 't1.organisation_id' => $organisation_id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $itemDonor = 0;
        foreach($resultSet as $set){
            $itemDonor = $set['donor_name'];
        }
        return $itemDonor;  
    }


	/**
	 * 
	 * @param type $ItemDonar
	 * 
	 * to save Item Donar
	 */

	public function saveItemDonor(ItemDonor $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_donor_details');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('item_donor_details');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}

	/**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to Delete Item Supplier
	 */

	public function deleteItemDonor(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('item_donor_details');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	} 



	public function listAllFixedAssetInStock($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_name'), // join table with alias
                    't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id')) //join expression
               ->join(array('t3' => 'item_quantity_type'), // join table with alias
                    't3.id = t2.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t4' => 'item_sub_category'), // join table with alias
                    't4.id = t2.item_sub_category_id', array('sub_category_type')) //join expression
               ->join(array('t5' => 'item_category'), // join table with alias
                    't5.id = t4.item_category_id', array('category_type'));
                
               $select->where(array('t4.organisation_id = ?' => $organisation_id, 't1.item_in_stock > 0', 't5.major_class_id' => '1'))
					  ->order('id ASC');


         	$stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array(); 
	}


	public function listAllConsumableAssetInStock($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_name'), // join table with alias
                    't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id')) //join expression
               ->join(array('t3' => 'item_quantity_type'), // join table with alias
                    't3.id = t2.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t4' => 'item_sub_category'), // join table with alias
                    't4.id = t2.item_sub_category_id', array('sub_category_type')) //join expression
               ->join(array('t5' => 'item_category'), // join table with alias
                    't5.id = t4.item_category_id', array('category_type'));
                
               $select->where(array('t4.organisation_id = ?' => $organisation_id, 't1.item_in_stock > 0',  't5.major_class_id' => '2'))
					  ->order('id ASC');


         	$stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array(); 
	}

    
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllPurchasedGoodsInStock($goodsCategory, $goodsSubCategory, $item_name_id, $organisation_id)
	{
		//get the id of the item name
		$item_sub_category_id = $goodsSubCategory;

		$item_name_id = $item_name_id;


            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_name'), // join table with alias
                    't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id')) //join expression
               ->join(array('t3' => 'item_quantity_type'), // join table with alias
                    't3.id = t2.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t4' => 'item_sub_category'), // join table with alias
                    't4.id = t2.item_sub_category_id', array('sub_category_type')) //join expression
               ->join(array('t5' => 'item_category'), // join table with alias
                    't5.id = t4.item_category_id', array('category_type')) //join expression
               ->join(array('t6' => 'item_received_purchased'),
           			't6.id = t1.item_received_purchased_id', array('supplier_details_id'));
                
               $select->where(array('t4.item_category_id' => $goodsCategory, 't4.id' => $item_sub_category_id, 't2.id' => $item_name_id, 'item_received_type' => 'Purchased', 't1.item_in_stock > 0', 't1.item_status' => 'Supplied'))
                      ->order('id ASC');


         	$stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array(); 
	}


	// Function to find the particular goods in stock
	public function findGoodsInStockDetails($id)
	{
		    $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_received_purchased'), // join table with alias
                    't2.id = t1.item_received_purchased_id', array('supplier_details_id', 'reference_no', 'reference_date')) 
               ->join(array('t3' => 'supplier_details'),
           			't3.id = t2.supplier_details_id', array('supplier_name', 'supplier_address'))
               ->join(array('t4' => 'item_name'), 
                    't4.id = t1.item_name_id', array('item_name'))
               ->where(array('t1.id = ?' => $id)); // join expression
            
                $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods In Stock with given ID: ($id) not found");
	}


	public function findDonatedGoodsInStockDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_received_donation'), // join table with alias
                    't2.id = t1.item_received_donation_id', array('item_donor_details_id')) 
               ->join(array('t3' => 'item_donor_details'),
           			't3.id = t2.item_donor_details_id', array('donor_name'))
               ->join(array('t4' => 'item_name'), 
                    't4.id = t1.item_name_id', array('item_name'))
               ->where(array('t1.id = ?' => $id)); // join expression
            
                $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods In Stock with given ID: ($id) not found");
	}


	public function findTransferedGoodsInStockDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_received')) //base table
           ->join(array('t2' => 'organisation_goods_transfer'), // join table with alias
                't2.id = t1.item_received_transfered_id', array('organisation_from_id', 'employee_details_from_id', 'employee_details_to_id', 'transfer_date', 'approve_date', 'transfer_quantity', 'transfer_remarks', 'approve_remarks')) 
           ->join(array('t3' => 'organisation'),
       			't3.id = t2.organisation_from_id', array('organisation_name'))
           ->join(array('t4' => 'item_name'), 
                't4.id = t1.item_name_id', array('item_name'))
           ->join(array('t5' => 'employee_details'),
       			't5.id = t2.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
           ->join(array('t6' => 'employee_details'),
       			't6.id = t2.employee_details_to_id', array('f_name' => 'first_name', 'm_name' => 'middle_name', 'l_name' => 'last_name', 'empId' => 'emp_id'))
           ->where(array('t1.id = ?' => $id, 't1.item_received_type' => 'Transfered')); // join expression
        
        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllDonationGoodsInStock($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_name'), // join table with alias
                    't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id')) //join expression
               ->join(array('t3' => 'item_quantity_type'), // join table with alias
                    't3.id = t2.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t4' => 'item_sub_category'), // join table with alias
                    't4.id = t2.item_sub_category_id', array('sub_category_type')) //join expression
               ->join(array('t5' => 'item_category'), // join table with alias
                   't5.id = t4.item_category_id', array('category_type')) //join expression
               ->join(array('t6' => 'item_received_donation'),
           			't6.id = t1.item_received_donation_id', array('item_donor_details_id'));
                
               $select->where(array('t6.organisation_id = ?' => $organisation_id, 'item_received_type' => 'Donation'))
                      ->order('item_received_date DESC');
                 


            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
	}


	public function listAllTransferedGoodsInStock($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_received')) //base table
           ->join(array('t2' => 'item_name'), // join table with alias
                't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id')) //join expression
           ->join(array('t3' => 'item_quantity_type'), // join table with alias
                't3.id = t2.item_quantity_type_id', array('item_quantity_type')) //join expression
           ->join(array('t4' => 'item_sub_category'), // join table with alias
                't4.id = t2.item_sub_category_id', array('sub_category_type')) //join expression
           ->join(array('t5' => 'item_category'), // join table with alias
               't5.id = t4.item_category_id', array('category_type')) //join expression
           ->join(array('t6' => 'organisation_goods_transfer'),
       			't6.id = t1.item_received_transfered_id', array('organisation_from_id', 'organisation_to_id'))
           ->join(array('t7' => 'organisation'),
       			't7.id = t6.organisation_from_id', array('organisation_name'))
            
           ->where(array('t4.organisation_id = ?' => $organisation_id, 't6.organisation_to_id = ?' => $organisation_id, 'item_received_type' => 'Transfered'))
                  ->order('item_received_date DESC');
             
        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listDeptGoodsInStock($departmentId, $organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'department_goods')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't2.id = t1.goods_received_id', array('item_name_id')) //join expression
               ->join(array('t3' => 'item_name'), // join table with alias
                    't3.id = t2.item_name_id', array('item_quantity_type_id', 'item_sub_category_id', 'item_name')) //join expression
               ->join(array('t4' => 'item_sub_category'), // join table with alias
                    't4.id = t3.item_sub_category_id', array('sub_category_type')) //join expression
               ->join(array('t5' => 'item_category'), // join table with alias
                    't5.id = t4.item_category_id', array('category_type')) //join expression
               ->join(array('t6' => 'item_quantity_type'),
           			't6.id = t3.item_quantity_type_id', array('item_quantity_type'))
               ->join(array('t7' => 'employee_details'),
           			't7.id = goods_received_by', array('first_name', 'middle_name', 'last_name', 'emp_id'));
                
               $select->where(array('t1.departments_id' => $departmentId, 't1.dept_quantity > 0', 't7.organisation_id' => $organisation_id, 't1.issue_goods_status' => 'Issued'))
                      ->order('id ASC');


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result); 
	}
	

	/**
	 * 
	 * @param type $GoodsReceivedDonation
	 * 
	 * to save GoodsReceivedPurchased
	 */


	public function saveGoodsReceivedPurchased(GoodsReceived $goodsPurchasedObject)
	{
		$goodsPurchasedData = $this->hydrator->extract($goodsPurchasedObject);
		unset($goodsPurchasedData['id']);
		unset($goodsPurchasedData['item_Received_Type']);
		unset($goodsPurchasedData['item_Entry_Date']);
		unset($goodsPurchasedData['item_Received_By']);
		unset($goodsPurchasedData['item_Received_Date']);
		unset($goodsPurchasedData['item_Verified_By']);
		unset($goodsPurchasedData['item_Donor_Details_Id']);
		unset($goodsPurchasedData['item_Purchasing_Rate']);
		unset($goodsPurchasedData['item_Quantity']);
		unset($goodsPurchasedData['item_Specification']);
		unset($goodsPurchasedData['item_Amount']);
		unset($goodsPurchasedData['item_In_Stock']);
		unset($goodsPurchasedData['item_Stock_Status']);
		unset($goodsPurchasedData['item_Status']);
		unset($goodsPurchasedData['remarks']);
		unset($goodsPurchasedData['item_Name_Id']);
		unset($goodsPurchasedData['item_Sub_Category_Id']);
		unset($goodsPurchasedData['item_Received_Purchased_Id']);
		unset($goodsPurchasedData['item_Received_Donation_Id']);
		unset($goodsPurchasedData['supplier_Name']);
		unset($goodsPurchasedData['organisation']);

		$goodsPurchasedData['reference_Date'] = date("Y-m-d", strtotime(substr($goodsPurchasedData['reference_Date'],0,10)));
		
		if($goodsPurchasedObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_received_purchased');
			$action->set($goodsPurchasedData);
			$action->where(array('id = ?' => $goodsPurchasedObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('item_received_purchased');
			$action->values($goodsPurchasedData);
		}

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsPurchasedObject->setId($newId);
			}
			return $goodsPurchasedObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveGoodsSupplied(GoodsReceived $goodsTransactionObject, $item_category_id, $item_sub_category_id, $id)
	{
     	$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['supplier_Name']);
		unset($goodsTransactionData['supplier_Details_Id']);
		unset($goodsTransactionData['reference_No']);
		unset($goodsTransactionData['reference_Date']);
		unset($goodsTransactionData['supplier_Order_No']);
		unset($goodsTransactionData['item_Donor_Details_Id']);
		unset($goodsTransactionData['item_Sub_Category_Id']);
		unset($goodsTransactionData['receipt_Voucher_No']);
		//unset($goodsTransactionData['organisation_Id']);

		$organisation_id = $goodsTransactionData['organisation_Id'];
		//$sub_category_type = $goodsTransactionData['item_Sub_Category_Id'];
		unset($goodsTransactionData['organisation_Id']);

		$goodsTransactionData['item_Name_Id'] = $id; 

		$goodsTransactionData['item_Received_Date'] = date("Y-m-d", strtotime(substr($goodsTransactionData['item_Received_Date'],0,10)));

		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('goods_received');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('goods_received');
			$action->values(array(
				'item_received_type' =>$goodsTransactionData['item_Received_Type'],
				'item_purchasing_rate' =>$goodsTransactionData['item_Purchasing_Rate'],
				'item_quantity' =>$goodsTransactionData['item_Quantity'],
				'item_specification' =>$goodsTransactionData['item_Specification'],
				'item_amount' =>$goodsTransactionData['item_Purchasing_Rate'] * $goodsTransactionData['item_Quantity'],
				'item_entry_date' =>$goodsTransactionData['item_Entry_Date'],
				'item_in_stock' =>$goodsTransactionData['item_Quantity'],
                'item_received_by' => $goodsTransactionData['item_Received_By'],
                'item_received_date' => $goodsTransactionData['item_Received_Date'],
                'item_verified_by' => $goodsTransactionData['item_Verified_By'],
                'item_status' => $goodsTransactionData['item_Status'],
                'item_stock_status' => $goodsTransactionData['item_Stock_Status'],
                'remarks' => $goodsTransactionData['remarks'],
                'item_name_id' => $goodsTransactionData['item_Name_Id'],
      			'item_received_purchased_id'=>$goodsTransactionData['item_Received_Purchased_Id']
			));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveGoodsReceiptVoucherNo($id, $organisation_id)
	{	

		//generate student id and assign to it
    	$goodsTransactionData['receipt_Voucher_No']  = $this->generateReceiptVoucherNo($organisation_id);

		//ID present, so it is an update
		$action = new Update('item_received_purchased');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
	}



	/**
	 * 
	 * @param type $GoodsReceivedDonation
	 * 
	 * to save GoodsReceivedDonation
	 */

	public function saveGoodsReceivedDonation(GoodsReceived $goodsTransactionObject, $item_category_id, $item_sub_category_id, $id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		$goodsTransactionDataSample = $goodsTransactionData;

		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Received_Purchased_Id']);
		unset($goodsTransactionData['item_Sub_Category_Id']);
		unset($goodsTransactionData['supplier_Name']);
		unset($goodsTransactionData['receipt_Voucher_No']);
		
		//defining an array and extracting elements of the Goods Received Model
		$goodsReceivedData= array();
		$goodsReceivedFields= array(
			'item_Received_Type',
		    'item_Purchasing_Rate',
		    'item_Quantity',
		    'item_Specification',
		    'item_Amount',
		    'item_Entry_Date',
		    'item_In_Stock',
		    'item_Stock_Status',
		    'item_Received_By',
		    'item_Received_Date',
		    'item_Updated_By',
		    'item_Verified_By',
		    'item_Status',
		    'remarks',
		    'item_Name_Id',
		    'item_Received_Donation_Id',
		    'reference_No',
		    'reference_Date',
		    'supplier_Order_No',
		    'supplier_Details_Id'
		);

		foreach ($goodsTransactionDataSample as $key => $value) {
			if(in_array($key, $goodsReceivedFields))
			{
				$goodsReceivedData = array_merge($goodsReceivedData,array($key=>$value));
				unset($goodsTransactionData[$key]);
			}
		}

		$organisation_id = $goodsTransactionData['organisation_Id'];
		//$sub_category_type = $goodsTransactionData['item_Sub_Category_Id'];
	//	unset($goodsTransactionData['organisation_Id']);

        $goodsReceivedData['item_Name_Id'] = $id; 
        $goodsReceivedData['item_Received_Date'] = date("Y-m-d", strtotime(substr($goodsReceivedData['item_Received_Date'],0,10)));
        
        //var_dump($goodsReceivedData);
        //die();
            if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('item_received_donation');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else{

		//ID is not present, so its an insert
		//this will not work for edit or update
		$action = new Insert('item_received_donation');
		$action->values($goodsTransactionData);
		}

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);

				//the following loop is to insert action plan
				if($goodsTransactionData !=  NULL)
				{
						$action = new Insert('goods_received');
						$action->values(array(
                            'item_received_type'=>$goodsReceivedData['item_Received_Type'],
                            'item_purchasing_rate' => $goodsReceivedData['item_Purchasing_Rate'],
                            'item_quantity' => $goodsReceivedData['item_Quantity'],
                            'item_specification' => $goodsReceivedData['item_Specification'],
                           // 'item_amount' => $goodsReceivedData['item_Amount'],
                            'item_entry_date' => $goodsReceivedData['item_Entry_Date'],
                            'item_in_stock' => $goodsReceivedData['item_Quantity'],
                            'item_stock_status' => $goodsReceivedData['item_Stock_Status'],
                            'item_received_by' => $goodsReceivedData['item_Received_By'],
                            'item_received_date' => $goodsReceivedData['item_Received_Date'],
                            'item_verified_by' => $goodsReceivedData['item_Verified_By'],
                            'item_status' => $goodsReceivedData['item_Status'],
                            'remarks' => $goodsReceivedData['remarks'],
                            'item_name_id' => $goodsReceivedData['item_Name_Id'],
                  			'item_received_donation_id'=>$newId));

						$sql = new Sql($this->dbAdapter);
						$stmt = $sql->prepareStatementForSqlObject($action);
						$result = $stmt->execute();
				}
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllSuppliedGoods($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_received_purchased')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't1.id = t2.item_received_purchased_id', array('item_received_purchased_id'))
                ->join(array('t3' => 'supplier_details'), // join table with alias
                     't3.id = t1.supplier_details_id', array('supplier_name', 'supplier_address', 'supplier_contact_no'))  //join
                ->where(array('t1.organisation_id = ?' .$organisation_id, 't1.receipt_voucher_no is NULL', 't2.item_status' => 'Supplied'))
                ->group(array('t1.reference_date','t1.supplier_order_no','t1.receipt_voucher_no','t1.supplier_details_id','t2.item_received_purchased_id', 't3.supplier_name', 't3.supplier_address', 't3.supplier_contact_no', 't1.reference_no'))
		->order(array('t1.id ASC')); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}

	public function findAllSuppliedGoodsVG($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_received_purchased')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't1.id = t2.item_received_purchased_id', array('item_received_purchased_id'))
                ->join(array('t3' => 'supplier_details'), // join table with alias
                     't3.id = t1.supplier_details_id', array('supplier_name', 'supplier_address', 'supplier_contact_no'))  //join
                ->where(array('t1.organisation_id = ?' .$organisation_id, 't1.receipt_voucher_no is NOT NULL', 't2.item_status' => 'Supplied'))
                ->group(array('t1.reference_date','t1.supplier_order_no','t1.receipt_voucher_no','t1.supplier_details_id','t2.item_received_purchased_id', 't3.supplier_name', 't3.supplier_address', 't3.supplier_contact_no', 't1.reference_no'))
		->order(array('t1.receipt_voucher_no DESC')); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @return array/GoodsTransaction()
	*/
	public function findSupplierAllGoodsDetails($id)
	{
		//$id = $item_received_purchased_id;
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_received')) //base table
                ->join(array('t2' => 'item_received_purchased'), // join table with alias
                    't2.id = t1.item_received_purchased_id', array('reference_no', 'reference_date', 'supplier_order_no'))
                ->join(array('t3' => 'supplier_details'), // join table with alias
                     't3.id = t2.supplier_details_id', array('supplier_name', 'supplier_tpn_no', 'supplier_address'))  //join expression
                ->join(array('t4' => 'item_name'),
                      't4.id = t1.item_name_id', array('item_name'))  //join expression
                ->join(array('t5' => 'item_sub_category'),
                	't5.id = t1.item_sub_category_id', array('sub_category_type')) //join expression
                ->join(array('t6' => 'item_quantity_type'),
                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')); //join expression

        $select->where(array('id = ? ' => $id));


        $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }


        /**
	* @return array/GoodsTransaction()
	*/
	public function generateGoodsReceiptVoucher($id)
	{
		//$id = $item_received_purchased_id;
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_received')) //base table
                ->join(array('t2' => 'item_received_purchased'), // join table with alias
                    't2.id = t1.item_received_purchased_id', array('reference_no', 'reference_date', 'supplier_order_no'))
                ->join(array('t3' => 'supplier_details'), // join table with alias
                     't3.id = t2.supplier_details_id', array('supplier_name', 'supplier_tpn_no', 'supplier_address'))  //join expression
                ->join(array('t4' => 'item_name'),
                      't4.id = t1.item_name_id', array('item_name'))  //join expression
                ->join(array('t5' => 'item_sub_category'),
                	't5.id = t1.item_sub_category_id', array('sub_category_type')) //join expression
                ->join(array('t6' => 'item_quantity_type'),
                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')); //join expression

        $select->where(array('id = ? ' => $id));


        $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }



	public function findIssueGoods($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('emp_goods');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Name with given ID: ($id) not found");
	}
	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllEmpIssuedGoods($departmentId, $organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'employee_details')) //base table
               ->join(array('t2' => 'emp_goods'), // join table with alias
                    't1.id = t2.employee_details_id', array('employee_details_id'))
               ->join(array('t3' => 'departments'),
               	    't3.id = t1.departments_id', array('department_name'))
               ->join(array('t4' => 'department_units'),
               	     't4.id = t1.departments_units_id', array('unit_name'))
               ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.departments_units_id' => $departmentId, 't2.emp_quantity > 0', 't2.issue_goods_status' => 'Issued'))
               ->group(array('t2.employee_details_id', 't3.department_name', 't4.unit_name'));
              // ->having('t2.emp_quantity > 0');        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllAdhocIssueGoods($tableName, $status, $employee_details_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => $tableName))
                   ->join(array('t2' => 'goods_received'),
                   		't2.id = t1.goods_received_id', array('item_name_id', 'item_in_stock', 'item_entry_date','item_received_date'))
                   ->join(array('t3' => 'item_name'),
                   		't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id'))
                   ->join(array('t4' => 'item_sub_category'),
                   		't4.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t5' => 'employee_details'),
               			't5.id = t1.employee_details_id', array('departments_id', 'first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->join(array('t6' => 'departments'),
               			't6.id = t5.departments_id', array('department_name'));
			$select->where(array('issue_goods_status = ? ' => $status, 't1.goods_issued_by = ?' => $employee_details_id))
				   ->order('id ASC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllRequisitionIssueGoods($tableName, $status, $employee_details_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => $tableName))
                   ->join(array('t2' => 'goods_received'),
                   		't2.id = t1.goods_received_id', array('item_name_id', 'item_in_stock', 'item_entry_date','item_received_date'))
                   ->join(array('t3' => 'item_name'),
                   		't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id'))
                   ->join(array('t4' => 'item_sub_category'),
                   		't4.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t5' => 'employee_details'),
               			't5.id = t1.employee_details_id', array('departments_id', 'first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->join(array('t6' => 'departments'),
               			't6.id = t5.departments_id', array('department_name'))
                   ->join(array('t7' => 'goods_requisition_details'),
               			't7.id = t1.goods_requisition_details_id', array('approved_balance_quantity'));
			$select->where(array('issue_goods_status = ? ' => $status, 't1.goods_issued_by = ?' => $employee_details_id, 't1.goods_requisition_details_id is NOT NULL'))
				   ->order('id ASC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}

    
    /*
	* List item to add issue goods etc
	*/
	
	public function getStaffList($empName, $empId, $department, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'departments'),
					't2.id = t1.departments_id', array('department_name'));
		//$select->column(array('id'));
		                  
		
		if($empName){
			$select->where->like('first_name','%'.$empName.'%');
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		}
		if($empId){
			$select->where(array('emp_id' =>$empId));
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		}
		if($department){
			$select->where->like('t2.department_name',$department.'%');
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		}


		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 

	}

	/*
	* List item to add issue goods etc
	*/
	
	public function getItemList($itemName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'item_name'));
		//$select->column(array('id'));
		                  
		
		if($itemName){
			$select->where->like('item_name','%'.$itemName.'%');
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 

	}


	/**
         * 
         * @param type $id
         * 
         * to find Adhoc Goods Issue Details with given $id
         */
        public function findAdhocGoodsIssueDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'emp_goods')) // base table
                   ->join(array('t2' => 'employee_details'),
               			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->where('t1.id = ' .$id); // join expression
            
                $stmt = $sql->prepareStatementForSqlObject($select);
                $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Sub Category with given ID: ($id) not found");
        }


    /**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/

	public function findAdhocGoodsIssue($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'emp_goods'))
        	   ->join(array('t2' => 'goods_received'),
        			't2.id = t1.goods_received_id', array('item_name_id'))
        	   ->join(array('t3' => 'item_name'),
        			't3.id = t2.item_name_id', array('item_name'));
        $select->where(array('t1.id = ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item Sub Category with given ID: ($id) not found");
	}


	/**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/

	public function findRequisitionGoodsIssue($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'emp_goods'))
        	   ->join(array('t2' => 'goods_received'),
        			't2.id = t1.goods_received_id', array('item_name_id'))
        	   ->join(array('t3' => 'item_name'),
        			't3.id = t2.item_name_id', array('item_name'));
        $select->where(array('t1.id = ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Issue Goods with given ID: ($id) not found");
	}


    /**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	public function findSubStoreGoodsIssue($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'department_goods'))
        	   ->join(array('t2' => 'goods_received'),
        			't2.id = t1.goods_received_id', array('item_name_id'))
        	   ->join(array('t3' => 'item_name'),
        			't3.id = t2.item_name_id', array('item_name'));
        $select->where(array('t1.id = ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item with given ID: ($id) not found");
	}


	/**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	public function findSubStoreToIndGoodsIssue($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'emp_goods'))
        	   ->join(array('t2' => 'department_goods'),
        			't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
        	   ->join(array('t3' => 'goods_received'),
        			't3.id = t2.goods_received_id', array('item_name_id'))
        	   ->join(array('t4' => 'item_name'),
        			't4.id = t3.item_name_id', array('item_name'));
        $select->where(array('t1.id = ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Item with given ID: ($id) not found");
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed
	 */
	public function getStaffDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) // base table
               ->join(array('t2' => 'departments'),
               	    't2.id = t1.departments_id', array('department_name'))
               ->join(array('t3' => 'department_units'),
               	     't3.id = t1.departments_units_id', array('unit_name'))
			   ->where('t1.id = ' .$id); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}

	public function getEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'employee_details'))
        	   ->join(array('t2' => 'departments'),
        			't2.id = t1.departments_id', array('department_name'))
        	   ->join(array('t3' => 'department_units'),
        			't3.id = t1.departments_units_id', array('unit_name'))
               ->where(array('t1.id =' .$id));  //join expression; //   

                $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();      

       if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Employee  with given ID: ($id) not found");
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed
	 */
	public function getStaffGoodsDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) // base table
               ->join(array('t2' => 'departments'),
               	    't2.id = t1.departments_id', array('department_name'))
               ->join(array('t3' => 'department_units'),
               	     't3.id = t1.departments_units_id', array('unit_name'))
               ->join(array('t4' => 'emp_goods'),
               	     't1.id = t4.employee_details_id', array('employee_details_id', 'goods_received_id', 'date_of_issue', 'emp_quantity', 'goods_code'))
               ->join(array('t5' => 'goods_received'),
               	     't5.id = t4.goods_received_id', array('item_name_id'))
               ->join(array('t6' => 'item_name'),
               	      't6.id = t5.item_name_id', array('item_name'))
               ->join(array('t7' => 'item_sub_category'),
               	      't7.id = t6.item_sub_category_id', array('sub_category_type'))
               ->join(array('t8' => 'item_quantity_type'),
               	      't8.id = t6.item_quantity_type_id', array('item_quantity_type'))
               ->join(array('t9' => 'item_category'),
               		  't9.id = t7.item_category_id', array('category_type'))
			   ->where(array('t1.id = ' .$id, 't4.issue_goods_status' => 'Issued', 't4.emp_quantity > 0')); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}

	/*
	 * Check for surrendered goods
	 */

	public function goodSurrenderedStatus($emp_id)
	{
		$list = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select()
			      ->from(array ('s1' =>'goods_surrender'))
		              ->columns(array('employee_details_id', 'emp_goods_id','goods_surrender_status'))
                              ->where(array('s1.employee_details_id' => $emp_id)); // join expression
                $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
                 //var_dump($result);
		foreach ($result as $row)
		{
			$list[$row['emp_goods_id']] = $row['goods_surrender_status'];
		}

		return $list;

	}

	/**
	 * Check for depreciation values
	 */

	public function getStaffDepreciationDetails($staffId)
	{
                	$sql = new Sql($this->dbAdapter);

			$status = 'Diminishing Balance';
                        $select = $sql->select();

                        $select->from(array('t1' => 'depreciation_table'),
                                      array('item_name_id','good_received_date','depreciation_rate', 'goods_life', 'scrap_value')) // Base table
                                ->join(array('t2' => 'item_name'), //join table with alias
                                                't2.id = t1.item_name_id', array('item_name')) // join expression
                                ->join(array('t3' => 'goods_received'),
					't3.item_name_id = t2.id', array('item_amount'))
				->join (array('t4' => 'goods_requisition_details'),
					't4.item_name_id = t3.item_name_id')
			        		;
                        $select->where(array( 't1.depreciation_method=?' => $status, 't4.employee_details_id = ?' => $staffId));

                        $stmt = $sql->prepareStatementForSqlObject($select);

			$result = $stmt->execute();

			foreach ($result as $dep) 
			{
                                $received_date = $dep['good_received_date']; // '2022-08-3';
                                $from = strtotime ($received_date);
                                $today = time ();
                                $difference = $today - $from;
			       
				$year = $dep['goods_life'];
				
				$depreciated = 0;

                                $lifeofthegoods = 365*$year;
                                $received_days = floor($difference / 86400);
				
			//	echo "<br />RD:".$received_days .'Days:'.$lifeofthegoods;
				
				$duration = $lifeofthegoods - $received_days;
				
				if (($lifeofthegoods - $received_days) < 0)
				{
			//		echo "VAL:".($lifeofthegoods - $received_days);
                                        $depreciation[$dep['item_name_id']][$staffId] = round(($dep['scrap_value']/100)*$dep['item_amount']);
                                }
				else {
					$oneday = (($dep['depreciation_rate']/100)/365);

					$balance = ($lifeofthegoods - $received_days);

					
                                        $depreciated = (($balance * $oneday)  * $dep['item_amount']);

						
                                        $depreciation[$dep['item_name_id']][$staffId] = round($depreciated);
                         
			       }	
			 	$depreciation[$staffId][$dep['item_name_id']] = $dep['good_received_date'];
                         
			 	$depreciation['amount'][$dep['item_name_id']] = $dep['item_amount'];
			
			 	$depreciation['scrap'][$dep['item_name_id']] = (($dep['scrap_value']/100) * $dep['item_amount']);

			 	$depreciation['depercent'][$dep['item_name_id']] = $dep['depreciation_rate']; 
                        
                }

                return $depreciation;
	}



	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed
	 */
	public function getDeptDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'departments')) // base table
			   ->where('t1.id = ' .$id); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllDeptIssueGoods($tableName, $status, $employee_details_id)
	{
           $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName))
                    ->join(array('t2' => 'goods_received'),
                   		't2.id = t1.goods_received_id', array('item_name_id', 'item_received_date', 'item_in_stock'))
                    ->join(array('t3' => 'item_name'),
                   		't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id'))
                   ->join(array('t4' => 'item_sub_category'),
                   		't4.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t5' => 'employee_details'),
                   		't5.id = t1.goods_received_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->join(array('t6' => 'departments'),
               			't6.id = t5.departments_id', array('department_name'));
			$select->where(array('t1.issue_goods_status = ? ' => $status, 't1.goods_issued_by = ? ' => $employee_details_id))
				   ->order('id ASC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}   

	/**
	* @return array/GoodsTransaction()
	*/
	public function findAllIndIssueGoods($tableName, $status, $employee_details_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'emp_goods'))
                    ->join(array('t2' => 'department_goods'),
                   		't2.id = t1.department_goods_id', array('goods_received_id', 'dept_quantity'))
                    ->join(array('t3' => 'goods_received'),
                		't3.id = t2.goods_received_id', array('item_name_id'))
                     ->join(array('t4' => 'item_name'),
                		't4.id = t3.item_name_id', array('item_name', 'item_quantity_type_id', 'item_sub_category_id'))
                    ->join(array('t5' => 'item_sub_category'),
                		't5.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                    ->join(array('t6' => 'item_category'),
                		't6.id = t5.item_category_id', array('category_type'))
                    ->join(array('t7' => 'item_quantity_type'),
                		't7.id = t4.item_quantity_type_id', array('item_quantity_type'))
                    ->join(array('t8' => 'employee_details'),
                		't8.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
			$select->where(array('t1.issue_goods_status = ? ' => $status))
				   ->where(array('t1.goods_issued_by = ?' => $employee_details_id))
				   ->order('id ASC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}  


	/*
	* List item to add issue goods etc
	*/
	
	/*public function getItemList($itemCategory, $itemSubCategory, $itemName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'goods_received')) //base table
               ->join(array('t2' => 'item_sub_category'), // join table with alias
                    't2.id = t1.item_sub_category_id', array('sub_category_type', 'item_category_type'))
                ->join(array('t3' => 'item_name'), // join table with alias
                     't3.id = t1.item_name_id', array('item_name'));  //join expression
		
		if($itemCategory){
			$select->where(array('item_category_type' =>$itemCategory));
		}
		if($itemSubCategory){
			$select->where(array('sub_category_type' =>$itemSubCategory));
		}
		if($itemName){
			$select->where(array('item_name' =>$itemName));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 

	}*/


	/**
         * 
         * @param type $id
         * 
         * to find Issue Goods with given $id
         */
        public function findIssueGoodsDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'emp_goods')); // base table
      
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }


        /**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	
	public function findEmpGoodsDetails($id)
	{
		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'emp_goods')) // base table
               ->join(array('t2' => 'goods_received'), // join table with alias
               	't2.id = t1.goods_received_id', array('item_name_id')) //join expression
               ->join(array('t3' => 'item_name'), // join table with alias
               	't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id')) //join expression
               ->join(array('t4' => 'item_sub_category'), // join table with alias
               	't4.id = t3.item_sub_category_id', array('sub_category_type')) //join expression
               ->join(array('t5' => 'item_quantity_type'), //join table with alias
               	't5.id = t3.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t6' => 'item_category'),
               	't6.id = t4.item_category_id', array('category_type'));
        $select->where(array('t1.id = ? ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods with given ID: ($id) not found"); 
	}


	public function crossCheckEmpGoodsSurrender($status, $id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'goods_surrender'))
				->columns(array('emp_goods_id'))
				->where(array('t1.goods_surrender_status' => $status, 't1.emp_goods_id' => $id, 't1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$empGoodsSurrender = NULL;
		foreach($resultSet as $set){
				$empGoodsSurrender= $set['emp_goods_id'];
		}
		return $empGoodsSurrender;
	}


	public function crossCheckEmpGoodsSurrenderQty($surrenderQuantity, $id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_goods'))
				->columns(array('emp_quantity'))
				->where(array('t1.id' => $id, 't1.emp_quantity < ?' => $surrenderQuantity, 't1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$goodsSurrenderQuantity = NULL;
		foreach($resultSet as $set){
				$goodsSurrenderQuantity= $set['emp_quantity'];
		}
		return $goodsSurrenderQuantity;
	}



	// Function to get the details of goods surrendered
	public function findGoodsSurrenderDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'goods_surrender')) // base table
               ->join(array('t2' => 'emp_goods'), // join table with alias
               		't2.id = t1.emp_goods_id', array('goods_received_id', 'goods_issued_remarks', 'goods_code')) //join expression
               ->join(array('t3' => 'goods_received'), // join table with alias
               		't3.id = t2.goods_received_id', array('item_name_id')) //join expression
               ->join(array('t4' => 'item_name'), // join table with alias
               		't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id')) //join expression
               ->join(array('t5' => 'item_quantity_type'), //join table with alias
               		't5.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t6' => 'item_sub_category'),
               		't6.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
               ->join(array('t7' => 'item_category'),
           			't7.id = t6.item_category_id', array('category_type'));
        $select->where(array('t1.id = ? ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods with given ID: ($id) not found");
	}


	// Function to get the details of the sub store goods surrendered 
	public function findSubStoreGoodsSurrenderDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'goods_surrender')) // base table
               ->join(array('t2' => 'emp_goods'), // join table with alias
               		't2.id = t1.emp_goods_id', array('goods_received_id', 'goods_issued_remarks')) //join expression
               ->join(array('t3' => 'goods_received'), // join table with alias
               		't3.id = t2.goods_received_id', array('item_name_id')) //join expression
               ->join(array('t4' => 'item_name'), // join table with alias
               		't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id')) //join expression
               ->join(array('t5' => 'item_quantity_type'), //join table with alias
               		't5.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t6' => 'item_sub_category'),
               		't6.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
               ->join(array('t7' => 'item_category'),
           			't7.id = t6.item_category_id', array('category_type'));
        $select->where(array('t1.id = ? ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods with given ID: ($id) not found");
	}


	public function findSubStoreSurrenderGoodsDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'sub_store_goods_surrender')) // base table
               ->join(array('t2' => 'department_goods'), // join table with alias
               		't2.id = t1.department_goods_id', array('goods_received_id', 'goods_issued_remarks', 'departments_id', 'goods_received_by', 'goods_issued_by', 'dept_quantity')) //join expression
               ->join(array('t3' => 'goods_received'), // join table with alias
               		't3.id = t2.goods_received_id', array('item_name_id')) //join expression
               ->join(array('t4' => 'item_name'), // join table with alias
               		't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id')) //join expression
               ->join(array('t5' => 'item_quantity_type'), //join table with alias
               		't5.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t6' => 'item_sub_category'),
               		't6.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
               ->join(array('t7' => 'item_category'),
           			't7.id = t6.item_category_id', array('category_type'))
               ->join(array('t8' => 'employee_details'),
           			't8.id = t1.surrender_by', array('first_name', 'middle_name', 'last_name', 'emp_id'));
        $select->where(array('t1.id = ? ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods with given ID: ($id) not found");
	}



        /*
	* List item to add issue goods to department etc
	*/
	
	public function getDeptList($department, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'departments')); //base table
		//$select->columns(array('id','department_name','organisation_id'));
             //  ->where('t1.organisation_id = ' .$organisation_id);
		
		if($department){
			$select->where->like('department_name','%'.$department.'%');
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 

	}


	/*
	* Get list of staff for evaluator list
	*/
	
	public function getDeptStaffList($id, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
		//$select->columns(array('id','first_name','middle_name','last_name','emp_id'))
		       ->join(array('t2' => 'departments'),
		   			't2.id = t1.departments_id', array('department_name'))
			   ->where(array('t1.organisation_id = ' .$organisation_id, 't2.id = ' .$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' '.$set['last_name']. ' ('.$set['emp_id'].')';
		}
		return $selectData;
	}


	/*
	* Get list of staff for evaluator list
	*/
	
	public function listSelectEmpData($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name','emp_id'))
			   ->where(array('t1.organisation_id = ' .$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' '.$set['last_name']. ' ('.$set['emp_id'].')';
		}
		return $selectData;
	}


	/*
	* Get list of Department Goods Receivers
	*/
	
	public function getGoodsReceiverList($organisation_id)
	{
		//need to get list of employees in organisation and store it in an array
		$i= 0;
		$employee_ids = array();
		$employeeData = $this->findAllEmployees($organisation_id);
		foreach($employeeData as $data)
		{
			$employee_ids[$i++] = $data['id'];
		}
				
		//get list of evaluators in organisation
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'department_goods'));
		$select->where(array('goods_received_by ' => $employee_ids));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}

	/*
	* get list of programmes given the organisation_id
	*/
	
	public function getDepartmentList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'departments'));
		$select->columns(array('id','department_name'))
				->where('t1.organisation_id = ' .$organisation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['department_name'];
		}
		return $selectData;
	}


	/*
	* get details of the evaluators
	*/
	
	public function getGoodsReceiverDetails($organisation_id)
	{
		//get the list of evaluators
		$i= 0;
		$employee_ids = array();
		$receiverList = $this->getGoodsReceiverList($organisation_id);
		foreach($receiverList as $data)
		{
			$employee_ids[$i++] = $data['goods_received_by'];
		}
		
		//get the details of evaluators in organisation
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name','emp_id'))
				->where(array('id ' => $employee_ids));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

   
   // To get the list of sub store nominee

	public function listAllSubStoreNominee($tableName, $departments_id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)) // join expression
            	   ->join(array('t2' => 'employee_details'),
            			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
            	   ->where(array('t2.departments_id = ?' => $departments_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
	}


	public function getGoodsIssueToEmployeeId($tableName, $employee_details_id)
	{
		preg_match("/(\w+\d+)/", $employee_details_id, $name_emp_id);
        foreach ($name_emp_id as $key => $value) {
        	$emp_id_to_insert = $value;
        }

        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.emp_id = ?' => $emp_id_to_insert));
			//$select->where(array('t2.organisation_id = ?' => $organisation_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


	/**
	 * 
	 * @param type $IssueGoods
	 * 
	 * to save IssueGoods
	 */

	public function saveAdhocIssueGoods(IssueGoods $goodsTransactionObject, $goods_received_id, $employee_details_id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);


		$temp_array = $goodsTransactionData['goods_Received_Id'];
      //  var_dump($temp_array);
        $item_name_ids = array();
        foreach($temp_array as $key=>$value1){
        		$item_name_ids[$value1] = $value1;
        }

        $goodsTransactionData['employee_Details_Id'] = $employee_details_id;
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		} else {
			//ID is not present, so its an insert
			foreach ($item_name_ids as $key => $value) {
				$action = new Insert('emp_goods');
				$action->values(array(
					'date_of_issue'=> $goodsTransactionData['date_Of_Issue'],
					'issue_goods_status'=> $goodsTransactionData['issue_Goods_Status'],
					'goods_issued_by'=> $goodsTransactionData['goods_Issued_By'],
					'employee_details_id'=> $goodsTransactionData['employee_Details_Id'],
					'goods_issued_remarks'=> $goodsTransactionData['goods_Issued_Remarks'],
					'goods_received_id'=> $value,
				));


				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			
	    }
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}

	/**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to Delete Item Supplier
	 */

	public function deleteAdhocGoodsIssue(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('emp_goods');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	/*
	* The following function to change the status from "not issued to issued"
	*/
	public function updateAdhocGoodsIssue($data_to_insert, $data_to_insert1)
	{
		/*var_dump($data_to_insert);
		var_dump($data_to_insert1);
		die();*/
		// Its an update
	
			foreach ($data_to_insert as $key => $value) {
				$goodsTransactionData['issue_Goods_Status'] = 'Issued';
				$goodsTransactionData['emp_Quantity'] = $value;			

				$action = new Update('emp_goods');
				$action->set($goodsTransactionData);
				$action->where(array('id = ?' => $key));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();

				//need to reduce the item in stock after the item has been issued
				$this->reduceAdhocInStock($key, $value);

				$this->addAdhocIssueItemCode($data_to_insert1);
		}
	}


	public function addAdhocIssueItemCode($data_to_insert1)
	{
		foreach ($data_to_insert1 as $key => $value1) {
			$goodsTransactionData['goods_Code'] = $value1;

			$action = new Update('emp_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $key));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
	}

	/**
	 * 
	 * @param type $IssueGoods
	 * 
	 * to save RequisitionGoodsIssue
	 */

	public function saveRequisitionIssueGoods(RequisitionIssueGoods $goodsTransactionObject, $goods_received_id, $employee_details_id, $goods_requisition_details_id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);

		$temp_array = $goodsTransactionData['goods_Received_Id'];
      //  var_dump($temp_array);
        $item_name_ids = array();
        foreach($temp_array as $key=>$value1){
        		$item_name_ids[$value1] = $value1;
        }

        $requisition_id = array();
        //$requisition_id = preg_replace("/\(([^()]*+|(?R))*\)/", "", $goods_requisition_details_id);
        $requisition_id = $goods_requisition_details_id;
       
        $goodsTransactionData['employee_Details_Id'] = $employee_details_id;

		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		} else {
			//ID is not present, so its an insert
			foreach ($item_name_ids as $key => $value) {
				$action = new Insert('emp_goods');
				$action->values(array(
					'date_of_issue'=> $goodsTransactionData['date_Of_Issue'],
					'issue_goods_status'=> $goodsTransactionData['issue_Goods_Status'],
					'goods_issued_by'=> $goodsTransactionData['goods_Issued_By'],
					'employee_details_id'=> $goodsTransactionData['employee_Details_Id'],
					'goods_issued_remarks'=> $goodsTransactionData['goods_Issued_Remarks'],
					'goods_code'=> $goodsTransactionData['goods_Code'],
					'goods_received_id'=> $value,
					'goods_requisition_details_id' => $requisition_id,
				));


				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			
	    }
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function deleteRequisitionGoodsIssue(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('emp_goods');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	/*
	* The following function to change the status from "not issued to issued"
	*/
	public function updateRequisitionGoodsIssue($data_to_insert, $data_to_insert1)
	{
		// Its an update
			foreach ($data_to_insert as $key => $value) {
				$goodsTransactionData['issue_Goods_Status'] = 'Issued';
				$goodsTransactionData['emp_Quantity'] = $value;

				$action = new Update('emp_goods');
				$action->set($goodsTransactionData);
				$action->where(array('id = ?' => $key));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();

				//need to reduce the item in stock after the item has been issued
				$this->reduceAdhocInStock($key, $value);
				$this->reduceRequisitionBalanceItem($key, $value);

				$this->addRequisitionIssueItemCode($data_to_insert1);
			}
	}


	public function addRequisitionIssueItemCode($data_to_insert1)
	{
		foreach ($data_to_insert1 as $key => $value1) {
			$goodsTransactionData['goods_Code'] = $value1;

			$action = new Update('emp_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $key));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
	}


	public function getGoodsSupplierDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'item_received_purchased'))
               ->join(array('t2' => 'supplier_details'),
                    't2.id = t1.supplier_details_id', array('supplier_name'))
               ->where(array('t1.id =' .$id));  //join expression; //   

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();      

       if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
        }
        throw new \InvalidArgumentException("Supplier  with given ID: ($id) not found");
	}


	public function getSuppliedGoodsList($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'item_received_purchased'))
               ->join(array('t2' => 'goods_received'),
               	    't1.id = t2.item_received_purchased_id', array('item_name_id', 'item_purchasing_rate', 'item_quantity', 'item_amount', 'item_received_date'))
               ->join(array('t3' => 'item_name'),
           			't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
               ->where(array('t1.id =' .$id, 't2.item_status' => 'Supplied'));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStoreManagerDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'employee_details'))
               ->where(array('t1.id = ?' => $employee_details_id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	 /* 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed
	 */
	public function goodsSupplierDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'item_received_purchased'))
               ->join(array('t2' => 'supplier_details'),
                    't2.id = t1.supplier_details_id', array('supplier_name', 'supplier_address'))
               ->join(array('t3' => 'organisation'),
           			't3.id = t1.organisation_id', array('organisation_name', 'address'))


               ->where(array('t1.id = ?' => $id, 't1.receipt_voucher_no IS NOT NULL'));  //join expression; //  
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	/**
	 * 
	 * @param type $IssueGoods
	 * 
	 * to save IssueGoods to sub store
	 */

	public function saveSubStoreIssueGoods(DeptGoods $goodsTransactionObject, $goods_received_id, $goods_received_by)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
        unset($goodsTransactionData['id']);

        $temp_array = $goodsTransactionData['goods_Received_Id'];
        $item_name_ids = array();
        foreach($temp_array as $key=>$value1){
        		$item_name_ids[$value1] = $value1;
        }

		$goodsTransactionData['goods_Received_By'] = $goods_received_by;
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('department_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
			
		} else {
			//ID is not present, so its an insert
			foreach ($item_name_ids as $key => $value) {
				$action = new Insert('department_goods');
				$action->values(array(
					'date_of_issue'=> $goodsTransactionData['date_Of_Issue'],
					'departments_id'=> $goodsTransactionData['departments_Id'],
					'issue_goods_status'=> $goodsTransactionData['issue_Goods_Status'],
					'goods_issued_by'=> $goodsTransactionData['goods_Issued_By'],
					'goods_received_by'=> $goodsTransactionData['goods_Received_By'],
					'goods_issued_remarks'=> $goodsTransactionData['goods_Issued_Remarks'],
					'goods_received_id'=> $value,
				));


				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			
	    }
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}

	/*
	* The following function to change the status from "not issued to issued"
	*/
	public function updateSubStoreIssueGoods($data_to_insert)
	{

        // Its an update
			foreach ($data_to_insert as $key => $value) {
				$goodsTransactionData['issue_Goods_Status'] = 'Issued';
				$goodsTransactionData['dept_Quantity'] = $value;


				$action = new Update('department_goods');
				$action->set($goodsTransactionData);
				$action->where(array('id = ?' => $key));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();

				//need to reduce the item in stock after the item has been issued
				$this->reduceInStock($key, $value);
			}
	}


	/**
	 * 
	 * @param type $ItemSupplier
	 * 
	 * to Delete Item Supplier
	 */

	public function deleteSubStoreGoodsIssue(GoodsTransaction $goodsTransactionObject)
	{

		$action = new Delete('department_goods');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	public function deleteSubStoreToIndIssueGoods(GoodsTransaction $goodsTransactionObject)
	{
		$action = new Delete('emp_goods');
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	/**
	 * 
	 * @param type $IssueGoods
	 * 
	 * to save IssueGoods to sub store  
	 */

	public function saveSubStoreToIndIssueGoods(DeptIssueGoods $goodsTransactionObject, $departments_id, $employee_details_id, $itemName)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['organisation_Id']);
        unset($goodsTransactionData['dept_Quantity']);
       // unset($goodsTransactionData['goods_Received_Id']);

        $goodsTransactionData['employee_Details_Id'] = $employee_details_id;

        $goodsTransactionData['goods_Received_Id'] = $itemName;
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_goods');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


   /**
	 * 
	 * @param type $NominationSubStore
	 * 
	 * to save NominationSubStore to sub store
	 */
	public function saveSubStoreNomination(NominateSubStore $goodsTransactionObject)
	{
        $goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);

		//var_dump($goodsTransactionData);
		//die();
		
		if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('sub_store_nominee');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('sub_store_nominee');
			$action->values($goodsTransactionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}

/*
	* The following function to change the status from "not issued to issued"
	*/
	public function updateSubStoreToIndIssueGoods($data_to_insert, $data_to_insert1)
	{
		// Its an update
			foreach ($data_to_insert as $key => $value) {
				$goodsTransactionData['issue_Goods_Status'] = 'Issued';
				$goodsTransactionData['emp_Quantity'] = $value;


				$action = new Update('emp_goods');
				$action->set($goodsTransactionData);
				$action->where(array('id = ?' => $key));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();

			    $this->reduceDeptGoodsInStock($key, $value);

			    $this->addSubStoreToIndIssueItemCode($data_to_insert1);
		}
	}



	public function addSubStoreToIndIssueItemCode($data_to_insert1)
	{
		foreach ($data_to_insert1 as $key => $value1) {
			$goodsTransactionData['goods_Code'] = $value1;

			$action = new Update('emp_goods');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $key));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
	}


	/*
	* The following function to change the status from "not issued to issued"
	*/
	public function updateDeptIssueGoods($status, $previousStatus)
	{
		//need to get the organisaiton id
		//$organisation_id = 1;
		$goodsTransactionData['issue_goods_status'] = $status;
		$action = new Update('department_goods');
		$action->set($goodsTransactionData);
		$action->where(array('issue_goods_status = ?' => $previousStatus));
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();


	}


    /**
	* @return array/GoodsTransaction()
	*/
	public function findEmpAllFixedAssetLists($employee_details_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'emp_goods')) //base table
               //->join(array('t2' => 'employee_details'), // join table with alias
                 //   't2.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name'))
                ->join(array('t3' => 'goods_received'), // join table with alias
                     't3.id = t1.goods_received_id', array('item_name_id'))  //join expression
                ->join(array('t4' => 'item_name'),
                      't4.id = t3.item_name_id', array('item_name', 'item_quantity_type_id','item_sub_category_id'))  //join expression
                ->join(array('t5' => 'item_sub_category'),
                	't5.id = t4.item_sub_category_id', array('sub_category_type')) //join expression
                ->join(array('t6' => 'item_quantity_type'),
                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
                ->join(array('t7' => 'item_category'),
                	't7.id = t5.item_category_id', array('category_type'))
                ->where(array('t1.employee_details_id = ?' => $employee_details_id))
                ->order(array('id ASC'))
				
                ->having(array('emp_quantity > 0'))
				->where->like('t7.major_class_id', "1");


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	public function listEmpAllConsumableGoodsLists($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'emp_goods')) //base table
               //->join(array('t2' => 'employee_details'), // join table with alias
                 //   't2.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name'))
                ->join(array('t3' => 'goods_received'), // join table with alias
                     't3.id = t1.goods_received_id', array('item_name_id'))  //join expression
                ->join(array('t4' => 'item_name'),
                      't4.id = t3.item_name_id', array('item_name', 'item_quantity_type_id','item_sub_category_id'))  //join expression
                ->join(array('t5' => 'item_sub_category'),
                	't5.id = t4.item_sub_category_id', array('sub_category_type')) //join expression
                ->join(array('t6' => 'item_quantity_type'),
                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
                ->join(array('t7' => 'item_category'),
                	't7.id = t5.item_category_id', array('category_type'))
                ->where(array('t1.employee_details_id = ?' => $employee_details_id, 't1.issue_goods_status' => 'Issued'))
                ->order(array('id ASC'))
                ->having(array('emp_quantity > 0'))
				->where->like('t7.major_class_id', "2");


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @return array/GoodsTransaction() to view surrender status by individual
	*/
	public function listEmpAllSurrenderedGoods($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_surrender')) //base table
               ->join(array('t2' => 'emp_goods'), // join table with alias
                    't2.id = t1.emp_goods_id', array('goods_received_id', 'employee_details_id'))
                ->join(array('t3' => 'goods_received'), // join table with alias
                     't3.id = t2.goods_received_id', array('item_name_id'))  //join expression
                ->join(array('t4' => 'item_name'),
                      't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id'))  //join expression
                ->join(array('t5' => 'item_sub_category'),
                	't5.id = t4.item_sub_category_id', array('sub_category_type')) //join expression
                ->join(array('t6' => 'item_quantity_type'),
                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
                ->join(array('t7' => 'employee_details'),
                	't7.id = t2.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name'))
                ->join(array('t8' => 'item_category'),
                	't8.id = t5.item_category_id', array('category_type'))
                ->where(array('t7.organisation_id = ?' => $organisation_id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	public function findAllGoodsSurrenderList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_surrender')) //base table
               ->join(array('t2' => 'emp_goods'), // join table with alias
                    't2.id = t1.emp_goods_id', array('goods_received_id'))
                ->join(array('t3' => 'goods_received'), // join table with alias
                     't3.id = t2.goods_received_id', array('item_name_id'))  //join expression
                ->join(array('t4' => 'item_name'),
                      't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id'))  //join expression
                ->join(array('t5' => 'item_sub_category'),
                	't5.id = t4.item_sub_category_id', array('sub_category_type')) //join expression
                ->join(array('t6' => 'item_quantity_type'),
                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
                ->join(array('t7' => 'item_category'),
                	't7.id = t5.item_category_id', array('category_type'))
                ->where(array('t1.employee_details_id = ?' => $employee_details_id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}



	/**
	* @return array/GoodsTransaction()
	*/
	public function listAllEmpSurrenderGoods($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'employee_details')) //base table
               ->join(array('t2' => 'goods_surrender'), // join table with alias
                    't1.id = t2.employee_details_id', array('employee_details_id', 'emp_goods_id', 'goods_surrender_date'))
               ->join(array('t3' => 'departments'),
               	    't3.id = t1.departments_id', array('department_name'))
               ->join(array('t4' => 'department_units'),
               	     't4.id = t1.departments_units_id', array('unit_name'))
               ->join(array('t5' => 'emp_goods'),
           			't5.id = t2.emp_goods_id', array('department_goods_id'))
               ->where(array('t2.goods_surrender_status' => 'Pending', 't1.organisation_id = ?' => $organisation_id, 't5.department_goods_id is NULL'));
               //->group(array('t1.id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	// Function to get the surrendered goods list applied staff to sub store
	public function listAllEmpSubStoreSurrenderGoods($organisation_id, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'employee_details')) //base table
               ->join(array('t2' => 'goods_surrender'), // join table with alias
                    't1.id = t2.employee_details_id', array('employee_details_id', 'emp_goods_id', 'goods_surrender_date'))
               ->join(array('t3' => 'departments'),
               	    't3.id = t1.departments_id', array('department_name'))
               ->join(array('t4' => 'department_units'),
               	     't4.id = t1.departments_units_id', array('unit_name'))
               ->join(array('t5' => 'emp_goods'),
           			't5.id = t2.emp_goods_id', array('department_goods_id'))
               ->join(array('t6' => 'department_goods'),
           			't6.id = t5.department_goods_id', array('departments_id'))
               ->where(array('t2.goods_surrender_status' => 'Pending', 't1.organisation_id = ?' => $organisation_id, 't6.departments_id = ?' => $departments_units_id, 't5.department_goods_id is NOT NULL'));
               //->group(array('t1.id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed and list of applied Goods Surreder 
	 */
	public function getStaffGoodsSurrenderDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_surrender'))
		       ->join(array('t2' => 'emp_goods'),
		       	      't2.id = t1.emp_goods_id', array('goods_received_id', 'employee_details_id'))
		       ->join(array('t3' => 'goods_received'),
		       	       't3.id = t2.goods_received_id', array('item_name_id'))
		       ->join(array('t4' => 'item_name'),
		       	       't4.id = t3.item_name_id', array('item_name', 'item_quantity_type_id', 'item_sub_category_id'))
		       ->join(array('t5' => 'item_sub_category'),
		       	       't5.id = t4.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t6' => 'item_quantity_type'),
		       	       't6.id = t4.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t7' => 'employee_details'),
		       	       't7.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t8' => 'department_units'),
		       	       't8.id = t7.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t9' => 'departments'),
		       	       't9.id = t8.departments_id', array('department_name'))
		       ->join(array('t10' => 'item_category'),
		       			't10.id = t5.item_category_id', array('category_type'))
               
			   ->where(array('t7.id = ' .$id, 'goods_surrender_status' => 'Pending')); // join expression
			   //->where(array('goods_surrender_status = Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed and list of applied Goods Surreder 
	 */
	public function getGoodsSurrenderDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_surrender'))
		       ->join(array('t2' => 'emp_goods'),
		       	      't2.id = t1.emp_goods_id', array('goods_received_id', 'employee_details_id', 'goods_code'))
		       ->join(array('t3' => 'goods_received'),
		       	       't3.id = t2.goods_received_id', array('item_name_id'))
		       ->join(array('t4' => 'item_name'),
		       	       't4.id = t3.item_name_id', array('item_name'))
		       ->join(array('t5' => 'item_sub_category'),
		       	       't5.id = t4.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t6' => 'item_quantity_type'),
		       	       't6.id = t4.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t7' => 'employee_details'),
		       	       't7.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t8' => 'department_units'),
		       	       't8.id = t7.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t9' => 'departments'),
		       	       't9.id = t8.departments_id', array('department_name'))
		        ->join(array('t10' => 'item_category'),
		       			't10.id = t5.item_category_id', array('category_type'))
               
			   ->where(array('t7.id =' .$id, 'goods_surrender_status' => 'Pending', 't2.department_goods_id is NULL')); // join expression
			   //->where(array('goods_surrender_status = Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	// Function to get the list of goods surrender applied to sub store
	public function getSubStoreGoodsSurrenderDetails($departments_units_id, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_surrender'))
		       ->join(array('t2' => 'emp_goods'),
		       	      't2.id = t1.emp_goods_id', array('goods_received_id', 'employee_details_id', 'goods_code'))
		       ->join(array('t3' => 'goods_received'),
		       	       't3.id = t2.goods_received_id', array('item_name_id'))
		       ->join(array('t4' => 'item_name'),
		       	       't4.id = t3.item_name_id', array('item_name'))
		       ->join(array('t5' => 'item_sub_category'),
		       	       't5.id = t4.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t6' => 'item_quantity_type'),
		       	       't6.id = t4.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t7' => 'employee_details'),
		       	       't7.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t8' => 'department_units'),
		       	       't8.id = t7.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t9' => 'departments'),
		       	       't9.id = t8.departments_id', array('department_name'))
		        ->join(array('t10' => 'item_category'),
		       			't10.id = t5.item_category_id', array('category_type'))
		        ->join(array('t11' => 'department_goods'),
		    			't11.id = t2.department_goods_id', array('departments_id'))
               
			   ->where(array('t7.id =' .$id, 't11.departments_id = ?' => $departments_units_id, 'goods_surrender_status' => 'Pending', 't2.department_goods_id is NOT NULL')); // join expression
			   //->where(array('goods_surrender_status = Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

    
    /**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
	*/
	
	public function findGoodsSurrenderList($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('goods_surrender');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods Surrender with given ID: ($id) not found");
	}


// Function to list all sub store surrender goods
	public function listAllSubStoreSurrenderGoods($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'department_units')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't1.id = t2.departments_id', array('goods_received_id', 'departments_id'))
               ->join(array('t3' => 'sub_store_goods_surrender'),
               	     't2.id = t3.department_goods_id', array('surrender_status'))
               ->join(array('t4' => 'departments'),
           			't4.id = t1.departments_id', array('organisation_id', 'department_name'))
               ->where(array('t3.surrender_status' => 'Pending', 't4.organisation_id' => $organisation_id))
               ->group(array('t2.departments_id'));

        /*$select->from(array('t1' => 'departments')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't1.id = t2.departments_id', array('goods_received_id', 'departments_id'))
               ->join(array('t3' => 'sub_store_goods_surrender'),
               	    't2.id = t3.department_goods_id', array('surrender_status'))
               ->join(array('t4' => 'department_units'),
               	     't1.id = t4.departments_id', array('unit_name'))
               ->where(array('t3.surrender_status' => 'Pending', 't1.organisation_id = ?' => $organisation_id));*/
             //  ->group(array('t2.employee_details_id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed and list of applied Goods Surreder 
	 */
	public function getSubStoreDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'sub_store_goods_surrender'))
		       ->join(array('t2' => 'department_goods'),
		       	      't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
		       ->join(array('t3' => 'department_units'),
		   				't3.id = t2.departments_id', array('unit_name', 'departments_id'))
		       ->join(array('t4' => 'departments'),
		   				't4.id = t3.departments_id', array('department_name'))
		       ->join(array('t5' => 'employee_details'),
		   				't3.id = t5.departments_units_id', array('emp_id', 'first_name', 'middle_name', 'last_name'))
		       ->where(array('t3.id = ?' => $id, 't1.surrender_status' => 'Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	public function getSubStoreSurrenderGoodsDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'sub_store_goods_surrender'))
		       ->join(array('t2' => 'department_goods'),
		       	      't2.id = t1.department_goods_id', array('goods_received_id', 'departments_id', 'goods_issued_remarks'))
		       ->join(array('t3' => 'goods_received'),
		       	       't3.id = t2.goods_received_id', array('item_name_id'))
		       ->join(array('t4' => 'item_name'),
		       	       't4.id = t3.item_name_id', array('item_name'))
		       ->join(array('t5' => 'item_sub_category'),
		       	       't5.id = t4.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t6' => 'item_quantity_type'),
		       	       't6.id = t4.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t7' => 'employee_details'),
		       	       't7.id = t1.surrender_by', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t8' => 'department_units'),
		       	       't8.id = t7.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t9' => 'departments'),
		       	       't9.id = t8.departments_id', array('department_name'))
		        ->join(array('t10' => 'item_category'),
		       			't10.id = t5.item_category_id', array('category_type'))
               
			   ->where(array('t8.id =' .$id, 't1.surrender_status' => 'Pending')); // join expression
			   //->where(array('goods_surrender_status = Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


// Function to list all sub store transfer goods
	public function listAllDeptTransferFrom($departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_transfer')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
               ->join(array('t3' => 'goods_received'),
               	    't3.id = t2.goods_received_id', array('item_name_id'))
               ->join(array('t4' => 'item_name'),
               	     't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
                ->join(array('t5' => 'item_sub_category'),
               	     't5.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                 ->join(array('t6' => 'item_category'),
               	     't6.id = t5.item_category_id', array('category_type'))
                 ->join(array('t7' => 'item_quantity_type'),
               	     't7.id = t4.item_quantity_type_id', array('item_quantity_type'))
                 ->join(array('t8' => 'department_units'),
               	     't8.id = t1.department_from_id', array('unit_name'))
               ->where(array('t1.goods_transfer_status' => 'Pending', 't1.department_to_id = ?' => $departments_units_id));
               //->group(array('t1.id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	public function listAllDeptTransferFromStatus($departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_transfer')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
               ->join(array('t3' => 'goods_received'),
               	    't3.id = t2.goods_received_id', array('item_name_id'))
               ->join(array('t4' => 'item_name'),
               	     't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
                ->join(array('t5' => 'item_sub_category'),
               	     't5.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                 ->join(array('t6' => 'item_category'),
               	     't6.id = t5.item_category_id', array('category_type'))
                 ->join(array('t7' => 'item_quantity_type'),
               	     't7.id = t4.item_quantity_type_id', array('item_quantity_type'))
                 ->join(array('t8' => 'department_units'),
               	     't8.id = t1.department_from_id', array('unit_name'))
               ->where(array('t1.department_to_id = ?' => $departments_units_id));
               //->group(array('t1.id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}



	public function listAllDeptTransferTo($departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_transfer')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
               ->join(array('t3' => 'goods_received'),
               	    't3.id = t2.goods_received_id', array('item_name_id'))
               ->join(array('t4' => 'item_name'),
               	     't4.id = t3.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
                ->join(array('t5' => 'item_sub_category'),
               	     't5.id = t4.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                 ->join(array('t6' => 'item_category'),
               	     't6.id = t5.item_category_id', array('category_type'))
                 ->join(array('t7' => 'item_quantity_type'),
               	     't7.id = t4.item_quantity_type_id', array('item_quantity_type'))
                 ->join(array('t8' => 'department_units'),
               	     't8.id = t1.department_to_id', array('unit_name'))
               ->where(array('t1.department_from_id = ?' => $departments_units_id));
               //->group(array('t1.id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	public function listAllOrgGoodsTransferApproval($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'organisation_goods_transfer')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't2.id = t1.organisation_goods_id', array('item_name_id'))
               ->join(array('t3' => 'item_name'),
               	    't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
               ->join(array('t4' => 'employee_details'),
               	     't4.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                ->join(array('t5' => 'item_sub_category'),
               	     't5.id = t3.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                 ->join(array('t6' => 'item_category'),
               	     't6.id = t5.item_category_id', array('category_type'))
                 ->join(array('t7' => 'item_quantity_type'),
               	     't7.id = t3.item_quantity_type_id', array('item_quantity_type'))
                 ->join(array('t8' => 'organisation'),
               	     't8.id = t1.organisation_from_id', array('organisation_name'))
               ->where(array('t1.transfer_status' => 'Pending', 't1.organisation_to_id = ?' => $organisation_id));
               //->group(array('t1.id'));        


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	// Function to find the details of organisation goods transfer
	public function findOrgGoodsTransferDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
    	$select = $sql->select();
		$select->from(array('t1' => 'organisation_goods_transfer')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't2.id = t1.organisation_goods_id', array('item_received_type', 'item_purchasing_rate', 'item_specification', 'item_name_id', 'item_stock_status', 'item_status', 'remarks', 'item_received_purchased_id', 'item_received_donation_id'))
               ->join(array('t3' => 'item_name'),
               	     't3.id = t2.item_name_id', array('item_name'))
                 ->join(array('t4' => 'organisation'),
               	     't4.id = t1.organisation_from_id', array('organisation_name'))
                 ->join(array('t5' => 'employee_details'),
             		  't5.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
          $select->where(array('t1.id = ? ' => $id));

		        $stmt = $sql->prepareStatementForSqlObject($select);
		        $result = $stmt->execute();

		        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
		            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		            }
		            throw new \InvalidArgumentException("Goods Transfer given ID: ($id) not found");
	}


	// Function to list all goods transfer to an organistaion
	public function listAllOrgGoodsTransferTo($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'organisation_goods_transfer')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't2.id = t1.organisation_goods_id', array('item_name_id'))
               ->join(array('t3' => 'item_name'),
               	    't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
               ->join(array('t4' => 'employee_details'),
               	     't4.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                ->join(array('t5' => 'item_sub_category'),
               	     't5.id = t3.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                 ->join(array('t6' => 'item_category'),
               	     't6.id = t5.item_category_id', array('category_type'))
                 ->join(array('t7' => 'item_quantity_type'),
               	     't7.id = t3.item_quantity_type_id', array('item_quantity_type'))
                 ->join(array('t8' => 'organisation'),
               	     't8.id = t1.organisation_to_id', array('organisation_name'))
               ->where(array('t1.organisation_from_id' => $organisation_id));
               //->group(array('t1.id'));        


       $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	// Function to list all goods transfer from organisation
	public function listAllOrgGoodsTransferFrom($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'organisation_goods_transfer')) //base table
               ->join(array('t2' => 'goods_received'), // join table with alias
                    't2.id = t1.organisation_goods_id', array('item_name_id'))
               ->join(array('t3' => 'item_name'),
               	    't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
               ->join(array('t4' => 'employee_details'),
               	     't4.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                ->join(array('t5' => 'item_sub_category'),
               	     't5.id = t3.item_sub_category_id', array('sub_category_type', 'item_category_id'))
                 ->join(array('t6' => 'item_category'),
               	     't6.id = t5.item_category_id', array('category_type'))
                 ->join(array('t7' => 'item_quantity_type'),
               	     't7.id = t3.item_quantity_type_id', array('item_quantity_type'))
                 ->join(array('t8' => 'organisation'),
               	     't8.id = t1.organisation_from_id', array('organisation_name'))
               ->where(array('t1.organisation_to_id = ?' => $organisation_id));
               //->group(array('t1.id'));        


       $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findOrgGoodsTransferToDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
    	$select = $sql->select();
		$select->from(array('t1' => 'organisation_goods_transfer')) //base table
               ->join(array('t2' => 'goods_received'),
               	    't2.id = t1.organisation_goods_id', array('item_name_id', 'item_specification'))
               ->join(array('t3' => 'item_name'),
               	     't3.id = t2.item_name_id', array('item_name'))
                 ->join(array('t4' => 'organisation'),
               	     't4.id = t1.organisation_to_id', array('organisation_name'))
                 ->join(array('t5' => 'employee_details'),
             		  't5.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
        $select->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findOrgGoodsTransferFromDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
    	$select = $sql->select();
		$select->from(array('t1' => 'organisation_goods_transfer')) //base table
               ->join(array('t2' => 'goods_received'),
               	    't2.id = t1.organisation_goods_id', array('item_name_id', 'item_specification'))
               ->join(array('t3' => 'item_name'),
               	     't3.id = t2.item_name_id', array('item_name'))
                 ->join(array('t4' => 'organisation'),
               	     't4.id = t1.organisation_from_id', array('organisation_name'))
                 ->join(array('t5' => 'employee_details'),
             		  't5.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
        $select->where(array('t1.id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	


	/**
	 * 
	 * @param type $IssueGoods
	 * 
	 * to save IssueGoods
	 */

	public function saveGoodsSurrender(GoodsSurrender $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
	    unset($goodsTransactionData['item_Category_Type']);
	    unset($goodsTransactionData['sub_Category_Type']);
	    unset($goodsTransactionData['item_Name']);
	    unset($goodsTransactionData['item_Quantity_Type']);
	    unset($goodsTransactionData['goods_Issued_Remarks']);
       

		
	/*	if($goodsTransactionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('goods_surrender');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		} else {*/
			//ID is not present, so its an insert
			$action = new Insert('goods_surrender');
			$action->values($goodsTransactionData);
		//}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function crossCheckDeptGoodsSurrender($status, $id, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'sub_store_goods_surrender'))
        	   ->join(array('t2' => 'department_goods'),
        			't1.department_goods_id = t2.id', array('departments_id'));
        $select->where(array('t1.department_goods_id' => $id, 't1.surrender_status' => $status, 't2.departments_id' => $departments_units_id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $deptGoodsTransfer = 0;
        foreach($resultSet as $set){
            $deptGoodsTransfer = $set['department_goods_id'];
        }
        return $deptGoodsTransfer;
	}

	public function crossCheckDeptGoodsSurrenderQty($id, $surrender_quantity)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'department_goods'));
        $select->where(array('t1.id' => $id, 't1.dept_quantity < ?' => $surrender_quantity));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $surrenderQty = 0;
        foreach($resultSet as $set){
            $surrenderQty = $set['dept_quantity'];
        }
        return $surrenderQty; 
	}


	public function crossCheckOrgGoodsTransferedQty($id, $itemTransferedQty)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'organisation_goods_transfer'));
        $select->where(array('t1.id' => $id, 't1.transfer_quantity < ?' => $itemTransferedQty));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $transferQty = 0;
        foreach($resultSet as $set){
            $transferQty = $set['transfer_quantity'];
        }
        return $transferQty; 
	}


	/**
	 * 
	 * @param type $IssueGoods
	 * 
	 * to save IssueGoods
	 */

	public function saveDeptGoodsSurrender(DeptGoodsSurrender $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
	    unset($goodsTransactionData['item_Category_Type']);
	    unset($goodsTransactionData['sub_Category_Type']);
	    unset($goodsTransactionData['item_Name']);
	    unset($goodsTransactionData['item_Quantity_Type']);
	    unset($goodsTransactionData['goods_Issued_Remarks']);
	    unset($goodsTransactionData['employee_Details_Id']);
	    unset($goodsTransactionData['departments_Id']);
	    unset($goodsTransactionData['unit_Name']);
	    unset($goodsTransactionData['department_Name']);


		$action = new Insert('sub_store_goods_surrender');
		$action->values($goodsTransactionData);
		//}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}



	public function updateSubStoreGoodsSurrender(DeptGoodsSurrender $goodsTransactionObject, $id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Category_Type']);
		unset($goodsTransactionData['sub_Category_Type']);
		unset($goodsTransactionData['item_Name']);
		unset($goodsTransactionData['item_Quantity_Type']);
		unset($goodsTransactionData['goods_Issued_Remarks']);
		unset($goodsTransactionData['employee_Details_Id']);
		unset($goodsTransactionData['departments_Id']);
		unset($goodsTransactionData['unit_Name']);
		unset($goodsTransactionData['department_Name']);

		//var_dump($id);
		//var_dump($goodsTransactionData);
		//die();
		
			//ID present, so it is an update
		$action = new Update('sub_store_goods_surrender');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($goodsTransactionData['surrender_Status'] == 'Approved')
		{
			$value = $this->getSubStoreSurrenderQuantity($tableName = 'sub_store_goods_surrender', $id);

			$this->reduceSubStoreGoodsInStock($id, $value);

			// To get departments_id from goods transfer
			$surrenderDeptId = $this->getDeptFromId($tableName = 'sub_store_goods_surrender', $id);

			// To get the goods_received_id from department_goods
		   	$goods_received_id = $this->getGoodsReceivedId($tableName = 'department_goods', $surrenderDeptId);

		   	$this->addGoodsInStock($goods_received_id, $value);
		}
	}


	/*
	* Reduce item in stock after item has been issued to the staff
	* Used by update goods issue function
	*/
	
	public function reduceInStock($id, $dept_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		$select = $sql->select();
		$select->from(array('t1' => 'department_goods'))
				->columns(array('goods_received_id'))
				->join(array('t2' => 'goods_received'),
					't2.id = t1.goods_received_id', array('goods_received_id'=>'id', 'item_in_stock'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_in_stock = $set['item_in_stock'];
			$goods_received_id = $set['goods_received_id'];
		}
		
		$goodsTransactionData['item_In_Stock'] = (int)$goods_in_stock- (int)$dept_quantity;
		$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goods_received_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}

	public function reduceDeptGoodsInStock($id, $emp_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'emp_goods'))
				->columns(array('department_goods_id'))
				->join(array('t2' => 'department_goods'),
					't2.id = t1.department_goods_id', array('department_goods_id'=>'id', 'dept_quantity'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_in_stock = $set['dept_quantity'];
			$department_goods_id = $set['department_goods_id'];
		}
		
		$goodsTransactionData['dept_Quantity'] = (int)$goods_in_stock- (int)$emp_quantity;
		$action = new Update('department_goods');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $department_goods_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function reduceDeptTransferGoods($id, $transfer_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'goods_transfer'))
				->columns(array('department_goods_id'))
				->join(array('t2' => 'department_goods'),
					't2.id = t1.department_goods_id', array('department_goods_id'=>'id', 'dept_quantity'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_in_stock = $set['dept_quantity'];
			$department_goods_id = $set['department_goods_id'];
		}
		
		$goodsTransactionData['dept_Quantity'] = (int)$goods_in_stock- (int)$transfer_quantity;
		$action = new Update('department_goods');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $department_goods_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}

	public function reduceAdhocInStock($id, $emp_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'emp_goods'))
				->columns(array('goods_received_id'))
				->join(array('t2' => 'goods_received'),
					't2.id = t1.goods_received_id', array('goods_received_id'=>'id', 'item_in_stock'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_in_stock = $set['item_in_stock'];
			$goods_received_id = $set['goods_received_id'];
		}
		
		$goodsTransactionData['item_In_Stock'] = (int)$goods_in_stock- (int)$emp_quantity;
		$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goods_received_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function reduceRequisitionBalanceItem($id, $emp_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'emp_goods'))
				->columns(array('goods_requisition_details_id'))
				->join(array('t2' => 'goods_requisition_details'),
					't2.id = t1.goods_requisition_details_id', array('goods_requisition_details_id'=>'id', 'approved_balance_quantity'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$requisition_balance = $set['approved_balance_quantity'];
			$goods_requisition_details_id = $set['goods_requisition_details_id'];
		}
		
		$goodsTransactionData['approved_Balance_Quantity'] = (int)$requisition_balance- (int)$emp_quantity;
		$action = new Update('goods_requisition_details');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goods_requisition_details_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function reduceStaffGoodsQuantity($id, $surrender_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'goods_surrender'))
				->columns(array('emp_goods_id'))
				->join(array('t2' => 'emp_goods'),
					't2.id = t1.emp_goods_id', array('emp_goods_id'=>'id', 'emp_quantity'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$emp_goods_quantity = $set['emp_quantity'];
			$emp_goods_id = $set['emp_goods_id'];
		}
		
		$goodsTransactionData['emp_Quantity'] = (int)$emp_goods_quantity- (int)$surrender_quantity;
		$action = new Update('emp_goods');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $emp_goods_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function addGoodsInStock($goods_received_id, $surrender_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'goods_received'))
				->columns(array('item_in_stock'))
				//->join(array('t2' => 'emp_goods'),
				//	't2.id = t1.emp_goods_id', array('emp_goods_id'=>'id', 'emp_quantity'))
				->where(array('t1.id = ?' => $goods_received_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_stock = $set['item_in_stock'];
			//$emp_goods_id = $set['emp_goods_id'];
		}
		
		$goodsTransactionData['item_In_Stock'] = (int)$goods_stock+ (int)$surrender_quantity;
		$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goods_received_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function addDeptGoodsInStock($department_goods_id, $surrender_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'department_goods'))
				->columns(array('dept_quantity'))
				//->join(array('t2' => 'emp_goods'),
				//	't2.id = t1.emp_goods_id', array('emp_goods_id'=>'id', 'emp_quantity'))
				->where(array('t1.id = ?' => $department_goods_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_stock = $set['dept_quantity'];
			//$emp_goods_id = $set['emp_goods_id'];
		}
		
		$goodsTransactionData['dept_Quantity'] = (int)$goods_stock+ (int)$surrender_quantity;
		$action = new Update('department_goods');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $department_goods_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function reduceEmpGoodsInStock($id, $emp_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		$select = $sql->select();
		$select->from(array('t1' => 'emp_goods'))
				->columns(array('emp_quantity'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$emp_goods_quantity = $set['emp_quantity'];
			//$emp_goods_id = $set['id'];
		}
		
		$goodsTransactionData['emp_Quantity'] = (int)$emp_goods_quantity- (int)$emp_quantity;
		$action = new Update('emp_goods');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}



	public function reduceSubStoreGoodsInStock($id, $surrender_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'sub_store_goods_surrender'))
				->columns(array('department_goods_id'))
				->join(array('t2' => 'department_goods'),
					't2.id = t1.department_goods_id', array('department_goods_id'=>'id', 'dept_quantity'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_in_stock = $set['dept_quantity'];
			$department_goods_id = $set['department_goods_id'];
		}
		
		$goodsTransactionData['dept_Quantity'] = (int)$goods_in_stock- (int)$surrender_quantity;
		$action = new Update('department_goods');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $department_goods_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}



    /*
	* 
	*/
	
	public function getAjaxItemDetailsDataId($tableName, $code, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.sub_category_type = ?' => $code));
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


	 /*
	* 
	*/
	
	public function getAjaxTransferEmployeeDataId($tableName, $code)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.emp_id = ?' => $code));
			//$select->where(array('t1.organisation_id = ?' => $organisation_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


	public function getEmpSurrenderQuantity($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('surrender_quantity'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['surrender_quantity'];
		}
		return $id;
	}


	public function getEmpGoodsId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('emp_goods_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['emp_goods_id'];
		}
		return $id;
	}


	public function getEmpGoodsReceivedId($tableName, $emp_goods_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('goods_received_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $emp_goods_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['goods_received_id'];
		}
		return $id;
	}


	public function getDeptGoodsId($tableName, $emp_goods_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('department_goods_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $emp_goods_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['department_goods_id'];
		}
		return $id;
	}


	public function getEmpGoodsInStock($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('emp_quantity'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['emp_quantity'];
		}
		return $id;
	}


	public function getDeptGoodsTransferQuantity($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('transfer_quantity'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['transfer_quantity'];
		}
		return $id;
	}


	public function getDeptFromId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		if($tableName == 'goods_transfer'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('department_goods_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		}
		else if($tableName == 'sub_store_goods_surrender'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('department_goods_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['department_goods_id'];
		}
		return $id;
	}


	public function getGoodsReceivedId($tableName, $goods_received_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('goods_received_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $goods_received_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['goods_received_id'];
		}
		return $id;
	}



	// Functtio to save the transfer goods from department to department into department_goods table
	public function addTransferGoodsToDeptGoods($goodsReceivedData, $departments_id, $date_of_issue, $goods_issued_by, $goods_received_by, $dept_quantity, $goods_issued_remarks)
	{
		$action = new Insert('department_goods');
		$action->values(array(
			'goods_received_id' => $goodsReceivedData,
			'departments_id' => $departments_id,
			'date_of_issue' => $date_of_issue,
			'issue_goods_status' => 'Issued',
			'goods_issued_by' => $goods_issued_by,
			'goods_received_by' => $goods_received_by,
			'dept_quantity' => $dept_quantity,
			'goods_issued_remarks' => $goods_issued_remarks
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}



	public function getOrgGoodsTransferQuantity($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('transfer_quantity'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['transfer_quantity'];
		}
		return $id;
	}


	public function getDisposedItemQuantity($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('item_quantity_disposed'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['item_quantity_disposed'];
		}
		return $id;
	}


	public function reduceOrgTransferGoods($id, $transfer_quantity)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get item in stock
		//$goods_in_stock = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'organisation_goods_transfer'))
				->columns(array('organisation_goods_id'))
				->join(array('t2' => 'goods_received'),
					't2.id = t1.organisation_goods_id', array('organisation_goods_id'=>'id', 'item_in_stock'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$goods_in_stock = $set['item_in_stock'];
			$organisation_goods_id = $set['organisation_goods_id'];
		}
		
		$goodsTransactionData['item_In_Stock'] = (int)$goods_in_stock- (int)$transfer_quantity;
		$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $organisation_goods_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function reduceItemQuantityInStock($id, $item_quantity_disposed)
	{
		$sql = new Sql($this->dbAdapter);
		
		$select = $sql->select();
		$select->from(array('t1' => 'goods_received'))
				->columns(array('item_in_stock'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$item_in_stock = $set['item_in_stock'];
			//$emp_goods_id = $set['id'];
		}
		
		$goodsTransactionData['item_in_stock'] = (int)$item_in_stock- (int)$item_quantity_disposed;
		$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		return;
	}


	public function addItemQuantityDisposed($id, $item_quantity_disposed)
	{
		$sql = new Sql($this->dbAdapter);
		
		$select = $sql->select();
		$select->from(array('t1' => 'goods_received'))
				->columns(array('item_quantity_disposed'))
				->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$quantity_disposed = $set['item_quantity_disposed'];
			//$emp_goods_id = $set['id'];
		}
		
		$goodsTransactionData['item_quantity_disposed'] = (int)$quantity_disposed + (int)$item_quantity_disposed;
		/*$action = new Update('goods_received');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();  */

		return;
	}


	// Functtio to save the transfer goods from organisation to organisation into goods_received table
	public function addTransferGoodsToOrgGoods($itemNameId, $itemReceivedType, $itemPurchasingRate, $itemTransferedId, $itemRemarks, $item_entry_date, $item_received_by, $item_quantity, $item_specification)
	{
		$action = new Insert('goods_received');
		$action->values(array(
			'item_name_id' => $itemNameId,
			'item_received_type' => $itemReceivedType,
			'item_purchasing_rate' => $itemPurchasingRate,
			'item_received_transfered_id' => $itemTransferedId,
			'remarks' => $itemRemarks,
			'item_entry_date' => $item_entry_date,
			'item_received_date' => $item_entry_date,
			'item_verified_by' => $item_received_by,
			'item_received_by' => $item_received_by,
			'item_quantity' => $item_quantity,
			'item_in_stock' => $item_quantity,
			'item_amount' => $item_quantity * $itemPurchasingRate,
			'item_stock_status' => 'Transfered Received Item',
			'item_status' => 'Transfered Item',
			'item_specification' => $item_specification
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}



	public function getOrgFromId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
	    $select->columns(array('organisation_goods_id'));
		//$select->where->like('id = ?' => $code);
		$select->where(array('t1.id = ?' => $id));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['organisation_goods_id'];
		}
		return $id;
	}


	public function getItemNameId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('item_name_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['item_name_id'];
		}
		return $id;
	}


	public function getItemReceivedType($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('item_received_type'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['item_received_type'];
		}
		return $id;
	}


	public function getItemPurchasingRate($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('item_purchasing_rate'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['item_purchasing_rate'];
		}
		return $id;
	}


	public function getItemPurchasedId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('item_received_purchased_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['item_received_purchased_id'];
		}
		return $id;
	}


	public function getItemDonatedId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('item_received_donation_id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['item_received_donation_id'];
		}
		return $id;
	}


	public function getItemRemarks($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('remarks'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['remarks'];
		}
		return $id;
	}



	public function getSubStoreSurrenderQuantity($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('surrender_quantity'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.id = ?' => $id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['surrender_quantity'];
		}
		return $id;
	}



	/*
	* 
	*/
	
	public function getAjaxItemSubCategoryId($tableName, $code, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.sub_category_type = ?' => $code));
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


	public function getAjaxItemNameId($tableName, $code, $item_sub_category_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
		    $select->columns(array('id'));
			//$select->where->like('id = ?' => $code);
			$select->where(array('t1.item_name = ?' => $code));
			$select->where(array('t1.item_sub_category_id = ?' => $item_sub_category_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}



	public function getAjaxDataId1($tableName, $code, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'item_sub_category'),
						't1.item_sub_category_id = t2.id', array('organisation_id'));
		   // $select->columns(array('id'));
			//$select->where->like('id = ?' => $code);
			//$select->join(array())
			$select->where(array('t1.item_name = ?' => $code));
			//$select->where(array('t2.organisation_id = ?' => $organisation_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}



	public function getAjaxEmployeeDataId($tableName, $code)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
		//if($tableName == 'item_sub_category'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.emp_id = ?' => $code));
			//$select->where(array('t2.organisation_id = ?' => $organisation_id));
		//}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


   // Function to get goods_received_id from department_goods to insert into emp_goods table
	public function getDeptGoodsReceivedId($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		
			$select->from(array('t1' => $tableName));
		    $select->columns(array('goods_received_id'));
			$select->where(array('t1.id = ?' => $id));


		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['goods_received_id'];
		}
		return $id;

	}


	/*
	* Return an id for the departments and units given the name
	* this is done as the ajax returns a value and not the id
	*/	
	public function getAjaxDataId($tableName, $code, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//$select->from(array('t1' => $tableName))
		//			->columns(array('id'));
			if($tableName == 'item_name'){
				$select->from(array('t1' => $tableName))
					 // ->columns(array('id'));
				      ->join(array('t2' => 'item_sub_category'),
						't1.item_sub_category_id = t2.id', array('organisation_id'));
			    $select->where(array('t1.item_name = ?' => $code));
				$select->where(array('t2.organisation_id = ?' => $organisation_id));
		}
		else if($tableName == 'employee_details'){
			$select->where(array('emp_id = ?' => $code));
		}
		else if($tableName == 'goods_received'){
			$select->where(array('id' => $code));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}



	/*
    * Function to generate the student id for new student
    */    
    public function generateReceiptVoucherNo($organisation_id)
    {
        
      $sql1 = new Sql($this->dbAdapter);
      $select1 = $sql1->select();

      $select1->from(array('t1' => 'organisation'));
      $select1->columns(array('organisation_code'));
      $select1->where(array('t1.id = ?' => $organisation_id));
      $stmt1 = $sql1->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);
        
        $code = NULL;
        foreach($resultSet1 as $set1)
            $code = $set1['organisation_code'];

        
        $Year = date('Y');
        $format = $code.substr($Year, 2).date('m');
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'item_received_purchased'))
               ->columns(array('receipt_voucher_no'));
        $select->where->like('receipt_voucher_no',''.$format.'%');
        $select->order('receipt_voucher_no DESC');
        $select->limit(1);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $receipt_voucher_no = NULL;
        
        foreach($resultSet as $set)
            $receipt_voucher_no = $set['receipt_voucher_no'];
        
        //first voucher of the year
        if($receipt_voucher_no == NULL){
            $generated_id = $code.substr(date('Y'),2).date('m').'001';
        }
        else{
            //need to get the last 4 digits and increment it by 1 and convert it back to string
            $number = substr($receipt_voucher_no, -3);
            $number = (int)$number+1;
            $number = strval($number);
            while (mb_strlen($number)<3)
                $number = '0'. strval($number);
            
            $generated_id = $code.substr(date('Y'),2).date('m').$number;
        }
        
        return $generated_id;
    }


	/**
	* @return array/GoodsTransaction()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the Item Sub Category field with Category Type from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}

	/**
	* @return array/GoodsTransaction()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the Item Sub Category field with Category Type from the database
	*/
	public function listSelectData2($tableName, $columnName, $organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
		       ->where(array('t1.organisation_id = ?' => $organisation_id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}

	/**
	* @return array/GoodsTransaction()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the Organisation Item Name field with Organisation Type from the database
	*/
	public function listSelectAddSubStoreData($tableName, $organisation_id)
	{

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();


        $select->from(array('t1' => $tableName))
		       ->join(array('t2' => 'item_name'),
		   			't2.id = t1.item_name_id', array('item_name'))
		       ->join(array('t3' => 'item_sub_category'),
		   			't3.id = t2.item_sub_category_id', array('organisation_id'))
		      
		      

		       ->where(array('t3.organisation_id = ?' => $organisation_id, 't1.item_status' => 'Supplied'))
		       ->order(array('id ASC'))
		       ->having(array('t1.item_in_stock > 0'));


		

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['item_name']. ' (' .$set['item_in_stock']. ')'. ' - '.$set['item_received_date']. ' (' .$set['item_specification']. ')';
		}
		return $selectData;
	}


    /**
	* @return array/GoodsTransaction()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the Department Item Name field with Department based on organisation Type from the database
	*/
	public function listSelectSubStoreToIndData($tableName, $departments_units_id, $employee_details_id)
	{
		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName))
		       ->join(array('t2' => 'goods_received'),
		   			't2.id = t1.goods_received_id', array('item_name_id'))
		       ->join(array('t3' => 'item_name'),
		   			't3.id = t2.item_name_id', array('item_name'))

		       ->where(array('t1.departments_id = ?' .$departments_units_id, 't1.goods_received_by = ?' .$employee_details_id, 't1.issue_goods_status' => 'Issued'))
		       ->order(array('id ASC'))
		       ->having(array('dept_quantity > 0'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['item_name']. ' ('.$set['dept_quantity'].' Qty)'. ' - '.$set['date_of_issue'];

		}
		return $selectData;
	}

	/**
	* @return array/GoodsTransaction()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the Item Sub Category field with Category Type from the database
	*/
	public function listSelectDataDetails($tableName, $columnName, $organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        if($tableName == 'supplier_details' && $organisation_id != NULL)
        {
        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
				->where(array('t1.organisation_id = ' .$organisation_id, 't1.supplier_status' => 'Active')); 
        }
        else if($tableName == 'item_donor_details' && $organisation_id != NULL){
        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
				->where('t1.organisation_id = ' .$organisation_id); 
        }

        else if($tableName == 'goods_received' && $organisation_id != NULL){
        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
				->where('t1.organisation = ' .$organisation_id); 
        }

        else if($tableName == 'departments' && $organisation_id != NULL){
        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
				->where('t1.organisation_id = ' .$organisation_id); 
        }

        else if($tableName == 'employee_details' && $organisation_id != NULL){
        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
				->where('t1.organisation_id = ' .$organisation_id); 
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listSelectEmpDetails($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name','emp_id'))
			   ->where(array('t1.organisation_id = ' .$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' '.$set['last_name']. ' ('.$set['emp_id'].')';
		}
		return $selectData;
	}



	public function updateEmpGoodsSurrender($status, $previousStatus, $id)
	{
		//$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);

		$emp_goods_id = $this->getEmpGoodsId($tableName = 'goods_surrender', $id);

		$goods_received_id = $this->getEmpGoodsReceivedId($tableName = 'emp_goods', $emp_goods_id);

		//var_dump($emp_goods_id);
		//var_dump($goods_received_id);
		//die();

		$goodsTransactionData['goods_surrender_status'] = $status;

		// To get the surrender quantity based on the goods_surrender_id
		$value = $this->getEmpSurrenderQuantity($tableName = 'goods_surrender', $id);

		$action = new Update('goods_surrender');
		$action->set($goodsTransactionData);
		if($previousStatus != NULL){
			$action->where(array('goods_surrender_status = ?' => $previousStatus));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->reduceStaffGoodsQuantity($id, $value);
		$this->addGoodsInStock($goods_received_id, $value);
		return;
	}


	// Function to update the approved goods surrendered to sub store from individual staff
	public function updateEmpSubStoreSurrender($status, $previousStatus, $id)
	{
		//$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);

		$emp_goods_id = $this->getEmpGoodsId($tableName = 'goods_surrender', $id);

		$department_goods_id = $this->getDeptGoodsId($tableName = 'emp_goods', $emp_goods_id);

		//var_dump($emp_goods_id);
		//var_dump($department_goods_id);
		// /die();

		$goodsTransactionData['goods_surrender_status'] = $status;

		// To get the surrender quantity based on the goods_surrender_id
		$value = $this->getEmpSurrenderQuantity($tableName = 'goods_surrender', $id);

		$action = new Update('goods_surrender');
		$action->set($goodsTransactionData);
		if($previousStatus != NULL){
			$action->where(array('goods_surrender_status = ?' => $previousStatus));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->reduceStaffGoodsQuantity($id, $value);
		$this->addDeptGoodsInStock($department_goods_id, $value);
		return;
	}


	public function updateEmpConsumableGoods($status, $previousStatus, $id)
	{
		//$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);

		$goodsTransactionData['issue_goods_status'] = $status;

		// To get the surrender quantity based on the goods_surrender_id
		$value = $this->getEmpGoodsInStock($tableName = 'emp_goods', $id);

		$action = new Update('emp_goods');
		$action->set($goodsTransactionData);
		if($previousStatus  != NULL){
			$action->where(array('issue_goods_status = ?' => $previousStatus));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->reduceEmpGoodsInStock($id, $value);
		return;
	}



	/*
	* Get list of staff for evaluator list
	*/
	
	public function listSelectItemVerify($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name','emp_id'))
			   ->where(array('t1.organisation_id = ' .$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' '.$set['last_name']. ' ('.$set['emp_id'].')';
		}
		return $selectData;
	}





	public function listSelectData1($tableName, $columnName)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
		$select->columns(array(DISTINCT($columnName))); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['columnName']] = $set[$columnName];
		}
		return $selectData;
	}


	
		public function findGoodsTransfer($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('tbl_goods_transfer');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
            }

            throw new \InvalidArgumentException("Goods  with given ID: ($id) not found");
	}
	
	/**
	* @return 
	*/
	public function findAllGoodsTransfer()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();



            $select->from(array('t1' => 'goods_transfer')); 


            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
/**
 * 
 * @param type $id
 * 
 * to find the Goods transfered for a given $id
 */
	public function findGoodsTransferDetail($id) 
	{
    		$sql = new Sql($this->dbAdapter);
    		$select = $sql->select();
    		$select->from(array('t1' => 'department_goods'));
    
    		$stmt = $sql->prepareStatementForSqlObject($select);
    		$result = $stmt->execute();

    if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
			$resultSet->buffer();
            return $resultSet->initialize($result); 
    }
    
    return array();
	}


	public function findDeptGoodsTransferFromDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
    	$select = $sql->select();
		$select->from(array('t1' => 'goods_transfer')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
               ->join(array('t3' => 'goods_received'),
               	    't3.id = t2.goods_received_id', array('item_name_id', 'item_specification'))
               ->join(array('t4' => 'item_name'),
               	     't4.id = t3.item_name_id', array('item_name'))
                 ->join(array('t5' => 'department_units'),
               	     't5.id = t1.department_from_id', array('unit_name'))
                 ->join(array('t6' => 'employee_details'),
             		  't6.id = t1.employee_details_from_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
          $select->where(array('t1.id = ? ' => $id));

		        $stmt = $sql->prepareStatementForSqlObject($select);
		        $result = $stmt->execute();

		        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
		            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		            }
		            throw new \InvalidArgumentException("Goods Transfer given ID: ($id) not found");
	}


	public function findDeptGoodsTransferToDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
    	$select = $sql->select();
		$select->from(array('t1' => 'goods_transfer')) //base table
               ->join(array('t2' => 'department_goods'), // join table with alias
                    't2.id = t1.department_goods_id', array('departments_id', 'goods_received_id'))
               ->join(array('t3' => 'goods_received'),
               	    't3.id = t2.goods_received_id', array('item_name_id', 'item_specification'))
               ->join(array('t4' => 'item_name'),
               	     't4.id = t3.item_name_id', array('item_name'))
                 ->join(array('t5' => 'department_units'),
               	     't5.id = t1.department_to_id', array('unit_name'))
                 ->join(array('t6' => 'employee_details'),
             		  't6.id = t1.employee_details_to_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
          $select->where(array('t1.id = ? ' => $id));

		        $stmt = $sql->prepareStatementForSqlObject($select);
		        $result = $stmt->execute();

		        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
		            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		            }
		            throw new \InvalidArgumentException("Goods Transfer given ID: ($id) not found");
	}
	

	public function crossCheckDeptGoodsTransfer($status, $id, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_transfer'));
        $select->where(array('t1.department_goods_id' => $id, 't1.goods_transfer_status' => $status, 't1.department_from_id' => $departments_units_id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $deptTransfer = 0;
        foreach($resultSet as $set){
            $deptTransfer = $set['department_goods_id'];
        }
        return $deptTransfer;
	}


	public function crossCheckDeptGoodsTransferQty($id, $transfer_quantity)
	{

		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'department_goods'));
        $select->where(array('t1.id' => $id, 't1.dept_quantity < ?' => $transfer_quantity));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $transferQty = 0;
        foreach($resultSet as $set){
            $transferQty = $set['dept_quantity'];
        }
        return $transferQty; 
	}
		
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */ 
	
	public function saveDeptGoodsTransfer(GoodsTransfer $goodsTransactionObject, $department_to_id, $employee_to_id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Category_Type']);
		unset($goodsTransactionData['sub_Category_Type']);
		unset($goodsTransactionData['item_Name']);
		unset($goodsTransactionData['department_Name']);
		unset($goodsTransactionData['item_Specification']);
		unset($goodsTransactionData['unit_Name']);

        $goodsTransactionData['department_To_Id'] = $department_to_id;

        //get the id of the item name
	    $goodsTransactionData['employee_Details_To_Id'] = $employee_to_id;
			
		$action = new Insert('goods_transfer');
	    $action->values($goodsTransactionData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateDeptGoodsTransfer(GoodsTransfer $goodsTransactionObject, $id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Name']);
		unset($goodsTransactionData['department_Name']);
		unset($goodsTransactionData['item_Specification']);
			
			//ID present, so it is an update
			$action = new Update('goods_transfer');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		   if($goodsTransactionData['goods_Transfer_Status'] == 'Approved'){

		   		$value = $this->getDeptGoodsTransferQuantity($tableName = 'goods_transfer', $id);

			   	//To reduce department quantity
			   	$this->reduceDeptTransferGoods($id, $value);

			   	// To get departments_id from goods transfer
			   	$fromDeptId = $this->getDeptFromId($tableName = 'goods_transfer', $id);

			   	// To get the goods_received_id from department_goods
		   	    $goodsReceivedId = $this->getGoodsReceivedId($tableName = 'department_goods', $fromDeptId);

		   	    // To add transfer goods into department_goods table
		   	    $this->addTransferGoodsToDeptGoods($goodsReceivedId, $goodsTransactionData['department_To_Id'], $goodsTransactionData['transfer_Update_Date'], $goodsTransactionData['employee_Details_From_Id'], $goodsTransactionData['employee_Details_To_Id'], $goodsTransactionData['transfer_Quantity'], $goodsTransactionData['transfer_Approved_Remarks']);

		   }
	}

	public function crossCheckOrgGoodsTransfer($status, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'organisation_goods_transfer'));
        $select->where(array('t1.transfer_status' => $status, 't1.organisation_goods_id' => $id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $orgGoodsTransfer = 0;
        foreach($resultSet as $set){
            $orgGoodsTransfer = $set['organisation_goods_id'];
        }
        return $orgGoodsTransfer;
	}


	public function crossCheckOrgGoodsTransferQty($transfer_quantity, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'goods_received'));
        $select->where(array('t1.id' => $id, 't1.item_in_stock < ?' => $transfer_quantity));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $transferQty = 0;
        foreach($resultSet as $set){
            $transferQty = $set['item_in_stock'];
        }
        return $transferQty;
	}	


  // To save the goods transfer applied by organisation
	public function saveOrgGoodsTransfer(OrgGoodsTransfer $goodsTransactionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Received_Purchased_Id']);
		unset($goodsTransactionData['item_Received_Donation_Id']);
		unset($goodsTransactionData['item_Name_Id']);
		unset($goodsTransactionData['item_Received_Type']);
		unset($goodsTransactionData['item_Purchasing_Rate']);			


		
		//ID is not present, so its an insert
		$action = new Insert('organisation_goods_transfer');
		$action->values($goodsTransactionData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsTransactionObject->setId($newId);
			}
			return $goodsTransactionObject;
		}
		
		throw new \Exception("Database Error");
	}



	public function updateOrgGoodsTransfer(OrgGoodsTransfer $goodsTransactionObject, $id, $item_category_id, $item_sub_category_id, $item_name_id, $itemTransferedId, $itemReceivedType, $organisation_id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Name']);
		unset($goodsTransactionData['organisation_Name']);
		unset($goodsTransactionData['item_Received_Purchased_Id']);
		unset($goodsTransactionData['item_Received_Donation_Id']);
		unset($goodsTransactionData['item_Received_Transfered_Id']);
		unset($goodsTransactionData['item_Name_Id']);
		unset($goodsTransactionData['item_Received_Type']);
		unset($goodsTransactionData['item_Purchasing_Rate']);
			
		//ID present, so it is an update
		$action = new Update('organisation_goods_transfer');
		$action->set($goodsTransactionData);
		$action->where(array('id = ?' => $goodsTransactionObject->getId()));
	
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

	   if($goodsTransactionData['transfer_Status'] == 'Approved'){

	   		$value = $this->getOrgGoodsTransferQuantity($tableName = 'organisation_goods_transfer', $id);

		   	//To reduce organisation quantity
		   	$this->reduceOrgTransferGoods($id, $value);

		   	// To get organisation_id from goods transfer
		   	$fromOrgId = $this->getOrgFromId($tableName = 'organisation_goods_transfer', $id);

	   	   $goodsTransactionData['item_Name_Id'] = $this->getAjaxDataId($tableName='item_name', $item_name_id, $organisation_id);
	   	   $itemTransferedId = $itemTransferedId;
			$itemReceivedType = $itemReceivedType;
			$itemPurchasingRate = $this->getItemPurchasingRate($tableName = 'goods_received', $fromOrgId);
			//$itemPurchasedId = $this->getItemPurchasedId($tableName = 'goods_received', $fromOrgId);
			//$itemDonatedId = $this->getItemDonatedId($tableName = 'goods_received', $fromOrgId);
			$itemRemarks = $this->getItemRemarks($tableName = 'goods_received', $fromOrgId);
			

	   	    // To add transfer goods into department_goods table
	   	    $this->addTransferGoodsToOrgGoods($goodsTransactionData['item_Name_Id'], $itemReceivedType, $itemPurchasingRate, $itemTransferedId, $itemRemarks, $goodsTransactionData['approve_Date'], $goodsTransactionData['employee_Details_To_Id'], $goodsTransactionData['transfer_Quantity'], $goodsTransactionData['approve_Remarks']);

		   }
	}


	public function rejectOrgFromGoodsTransfer($status, $previousStatus, $id, $employee_details_id)
	{
		$goodsTransactionData['transfer_Status'] = $status;
		$goodsTransactionData['employee_Details_To_Id'] = $employee_details_id;
		$goodsTransactionData['approve_Date'] = date('Y-m-d');
		$goodsTransactionData['approve_Remarks'] = "Goods Transfer has been rejected";

        $action = new Update('organisation_goods_transfer');
        $action->set($goodsTransactionData);
        if($previousStatus != NULL){
            $action->where(array('transfer_status = ?' => $previousStatus));
        } elseif($id != NULL){
            $action->where(array('id = ?' => $id));
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return;
	}


	public function updateDisposeGoods(DisposeGoods $goodsTransactionObject, $id)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsTransactionObject);
		unset($goodsTransactionData['id']);
		unset($goodsTransactionData['item_Name']);


			$value1 = $this->getDisposedItemQuantity($tableName = 'goods_received', $id);

			//$disposed_item_quantity = $this->addItemQuantityDisposed($id, $value1);

			$goodsTransactionData['item_quantity_disposed'] = $value1 + $goodsTransactionData['item_Quantity_Disposed'];

			$this->reduceItemQuantityInStock($id, $goodsTransactionData['item_Quantity_Disposed']);
			
			//ID present, so it is an update
			$action = new Update('goods_received');
			$action->set($goodsTransactionData);
			$action->where(array('id = ?' => $goodsTransactionObject->getId()));
		
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		   //	$value = $this->getDisposedItemQuantity($tableName = 'goods_received', $id);

			//To reduce organisation quantity
			
	}


	public function findTransferGoodsApprovalStatus()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'tbl_goods_transfer')); 


            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	public function findDeptGoodsDetails($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'department_goods')) // base table
               ->join(array('t2' => 'departments'), // join table with alias
               		't2.id = t1.departments_id', array('department_name')) //join expression
               ->join(array('t3' => 'goods_received'), // join table with alias
               		't3.id = t1.goods_received_id', array('item_name_id')) //join expression
               ->join(array('t4' => 'item_name'), // join table with alias
               		't4.id = t3.item_name_id', array('item_name')) //join expression
               ->join(array('t5' => 'item_quantity_type'), //join table with alias
               		't5.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
               ->join(array('t6' => 'item_sub_category'),
               		't6.id = t4.item_sub_category_id', array('sub_category_type'))
               ->join(array('t7' => 'item_category'),
               		't7.id = t6.item_category_id', array('category_type'))
        	   ->where(array('t1.id = ?' => $id));

		        $stmt = $sql->prepareStatementForSqlObject($select);
		        $result = $stmt->execute();

		        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
		            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		            }
		            throw new \InvalidArgumentException("Goods with given ID: ($id) not found");
	}


	public function findOrgGoodsDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'goods_received')) // base table
               ->join(array('t2' => 'item_name'), // join table with alias
               		't2.id = t1.item_name_id', array('item_name')) //join expression
               ->join(array('t3' => 'item_sub_category'), // join table with alias
               		't3.id = t2.item_sub_category_id', array('sub_category_type', 'item_category_id', 'organisation_id')) //join expression
               ->join(array('t4' => 'item_quantity_type'), // join table with alias
               		't4.id = t2.item_quantity_type_id', array('item_quantity_type')) //join expression
        	   ->where(array('t1.id = ?' => $id));

		        $stmt = $sql->prepareStatementForSqlObject($select);
		        $result = $stmt->execute();

		        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
		            return $this->hydrator->hydrate($result->current(), $this->goodsTransactionPrototype);
		            }
		            throw new \InvalidArgumentException("Goods with given ID: ($id) not found");
	}

	
	/**
	* @return array/GoodsTransaction()
	*/
	public function findDeptAllGoods($tableName, $departments_units_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'department_goods'){
	        $select->from(array('t1' => $tableName)) //base table
	               ->join(array('t2' => 'department_units'), // join table with alias
	                    't2.id = t1.departments_id', array('unit_name'))
	                ->join(array('t3' => 'goods_received'), // join table with alias
	                     't3.id = t1.goods_received_id', array('item_name_id'))  //join expression
	                ->join(array('t4' => 'item_name'),
	                      't4.id = t3.item_name_id', array('item_name'))  //join expression
	                ->join(array('t5' => 'item_sub_category'),
	                	't5.id = t4.item_sub_category_id', array('sub_category_type')) //join expression
	                ->join(array('t6' => 'item_quantity_type'),
	                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
	                ->join(array('t7' => 'item_category'),
	                	't7.id = t5.item_category_id', array('category_type'))
	                ->where(array('t1.departments_id = ?' => $departments_units_id, 't1.dept_quantity > 0'));
            }


        else if($tableName == 'goods_transfer'){
	        $select->from(array('t1' => $tableName)) //base table
	               ->join(array('t2' => 'department_goods'), // join table with alias
	                    't2.id = t1.department_goods_id', array('goods_received_id'))
	                ->join(array('t3' => 'goods_received'), // join table with alias
	                     't3.id = t2.goods_received_id', array('item_name_id'))  //join expression
	                ->join(array('t4' => 'item_name'),
	                      't4.id = t3.item_name_id', array('item_name'))  //join expression
	                ->join(array('t5' => 'item_sub_category'),
	                	't5.id = t4.item_sub_category_id', array('sub_category_type')) //join expression
	                ->join(array('t6' => 'item_quantity_type'),
	                	't6.id = t4.item_quantity_type_id', array('item_quantity_type')) //join expression
	                ->join(array('t7' => 'item_category'),
	                	't7.id = t5.item_category_id', array('category_type'))
	                ->join(array('t8' => 'department_units'),
	                	't8.id = t1.department_to_id', array('unit_name'))
	                ->where(array('t1.department_to_id = ?' => $departments_units_id, 't1.goods_transfer_status' => 'Approved', 't1.transfer_quantity > 0'));
            }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsTransactionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}

	public function getOrganisationDocument($tableName, $document_type, $organisation_id)
	{	
		
		$sql = new Sql($this->dbAdapter);

		if($tableName == 'organisation_document'){
			$img_location = NULL;

			$select = $sql->select();

			$select->from(array('t1' => $tableName))
		       ->where(array('t1.document_type' => $document_type, 't1.organisation_id' => $organisation_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$img_location = $set['documents'];
			}
			return $img_location;
		} 
		else if($tableName == 'organisation'){ 
			$select = $sql->select();

			$select->from(array('t1' => $tableName))
		       ->where(array('t1.id' => $organisation_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
	}

	public function getOrganizationDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'organisation')) // base table
				->where('t1.id = ' .$id); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();

		return $resultSet->initialize($result);
		
	}

}
