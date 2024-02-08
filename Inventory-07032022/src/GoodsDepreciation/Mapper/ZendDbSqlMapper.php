<?php

namespace GoodsDepreciation\Mapper;

use GoodsDepreciation\Model\GoodsDepreciation;
use GoodsDepreciation\Model\FixedAsset;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\where;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements GoodsDepreciationMapperInterface
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
	 * @var \GoodsDepreciation\Model\GoodsDepreciationInterface
	*/
	protected $goodsDepreciationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			//\stdClass $goodsTransactionPrototype
			GoodsDepreciation $goodsDepreciationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->goodsDepreciationPrototype = $goodsDepreciationPrototype;
	}


	public function getUserDetailsId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('id', 'departments_units_id', 'departments_id'));
			
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
	* @return array/GoodsDepreciation()
	*/
	public function findAllFixedAssets()
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'item_name')) //base table
               ->join(array('t2' => 'item_sub_category'), //join table with alias
               		't2.id = t1.item_sub_category_id', array('item_category_id', 'sub_category_type'))
               ->join(array('t3' => 'item_category'),
               		't3.id = t2.item_category_id', array('category_type'))
               ->join(array('t4' => 'item_quantity_type'),
               		't4.id = t1.item_quantity_type_id', array('item_quantity_type')); 
        $select->where(array('t3.category_type LIKE "%Fixed%"'))
               ->order('id ASC');


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsDepreciationPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
         * 
         * @param type $id
         * 
         * to find Fixed Asset Details with given $id
         */
        public function findFixedAssetDetails($id) 
        {
        	//var_dump($id); die;
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'item_name')) // base table
            	   ->join(array('t2' => 'item_sub_category'),
            	   		't2.id = t1.item_sub_category_id', array('sub_category_type', 'item_category_id'))
            	   ->join(array('t3' => 'item_quantity_type'),
            	   		't3.id = t1.item_quantity_type_id', array('item_quantity_type'))
            	   ->join(array('t4' => 'item_category'),
            			't4.id = t2.item_category_id', array('category_type'))
                   ->where('t1.id = ?' .$id); // join expression
            
            $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->goodsDepreciationPrototype);
            }

            throw new \InvalidArgumentException("Goods with given ID: ($id) not found");
        }



	/**
	 * 
	 * @param type $DepreciationValue
	 * 
	 * to save DepreciationValue
	 */

	public function saveDepreciationValue(FixedAsset $goodsDepreciationObject)
    {
		$goodsDepreciationData = $this->hydrator->extract($goodsDepreciationObject);
		unset($goodsDepreciationData['id']);
		unset($goodsDepreciationData['item_Category_Type']);
		unset($goodsDepreciationData['sub_Category_Type']);
		unset($goodsDepreciationData['item_Name']);
		unset($goodsDepreciationData['item_Quantity_Type']);

		if($goodsDepreciationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('depreciation_table');
			$action->set($goodsDepreciationData);
			$action->where(array('id = ?' => $goodsDepreciationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('depreciation_table');
			$action->values($goodsDepreciationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsDepreciationObject->setId($newId);
			}
			return $goodsDepreciationObject;
		}
		
		throw new \Exception("Database Error");
	} 


	/**
	* @return array/GoodsDepreciation()
	*/
	public function findAllDepreciationValue()
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'depreciation_table')) //base table
               ->join(array('t2' => 'item_name'),
               		't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id'))
               ->join(array('t3' => 'item_sub_category'),
               		't3.id = t2.item_sub_category_id', array('sub_category_type'))
               ->join(array('t4' =>'item_quantity_type'),
               		't4.id = t2.item_quantity_type_id', array('item_quantity_type'))
               ->order('id ASC');


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsDepreciationPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
}