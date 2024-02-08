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
/*
		$select->from(array('t1' => 'item_name')) //base table
               ->join(array('t2' => 'item_sub_category'), //join table with alias
                        't2.id = t1.item_sub_category_id', array('item_category_id', 'sub_category_type'))
               ->join(array('t3' => 'item_category'),
                        't3.id = t2.item_category_id', array('category_type'))
               ->join(array('t4' => 'item_quantity_type'),
		       't4.id = t1.item_quantity_type_id', array('item_quantity_type'));
 */

		$select-> //from (array('emp' => 'employee_details'), array('emp_id'))
                       //->join(array('egoods' => 'goods_requisition_details'),
                        //	'egoods.employee_details_id = emp.id', array('employee_details_id'))
                       from(array('t1' => 'item_name')) 
                       ->join(array('g1' => 'goods_received'),
                                 'g1.item_name_id = t1.id', array('item_received_date'))	
               	       ->join(array('t2' => 'item_sub_category'), //join table with alias
               			't2.id = t1.item_sub_category_id', array('item_category_id', 'sub_category_type'))
                       ->join(array('t3' => 'item_category'),
               			't3.id = t2.item_category_id', array('category_type'))
               	       ->join(array('t4' => 'item_quantity_type'),
               			't4.id = t1.item_quantity_type_id', array('item_quantity_type')); 
		$select->where(array('t3.major_class_id=1'))
              		 ->order('g1.item_received_date DESC');
	      
        	$stmt = $sql->prepareStatementForSqlObject($select);
	
		$result = $stmt->execute();
        	if ($result instanceof ResultInterface && $result->isQueryResult()) {

            		$resultSet = new ResultSet();
            		$resultSet->initialize($result);

            		$resultSet = new HydratingResultSet($this->hydrator, $this->goodsDepreciationPrototype);
                    		return $resultSet->initialize($result); 
            	}

	 	throw new \InvalidArgumentException("Asset is not found or entered");
	 
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
	    $select->from(array('t1' => 'item_name'))
		   ->join(array('g1' => 'goods_received'),
                                 'g1.item_name_id = t1.id', array('item_received_date'))
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

	/*
	 * Find depreciated value 
	 */
	public function findAllDepreciatedFixedAssets()
	{
		$depreciatedVal = array();
		$sql = new Sql($this->dbAdapter);
            	$select = $sql->select();
            	$select->from(array('t1' => 'item_name'))
                   ->join(array('d1' => 'depreciation_table'),
                                 'd1.item_name_id = t1.id', array('item_name_id'));

            	$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		//	var_dump($result);
		foreach ($result as $row)
		{
			$depreciatedVal[$row['item_name_id']] = 'Update';
		}

		return $depreciatedVal;
	}

	/**
	 * 
	 * @param type $DepreciationValue
	 * 
	 * to save DepreciationValue
	 * Model: FixedAsset
	 */

	public function saveDepreciationValue( $goodsDepreciationObject)
       {
		$goodsDepreciationData = $this->hydrator->extract($goodsDepreciationObject);
		$depreciationValue['item_name_id'] = $goodsDepreciationObject->getItem_Name_Id();
		$depreciationValue['good_received_date'] = $goodsDepreciationObject->getItem_Received_Date();
		$depreciationValue['depreciation_method'] = $goodsDepreciationObject->getDepreciation_Method();
		$depreciationValue['depreciation_rate'] = $goodsDepreciationObject->getDepreciation_Rate();
		$depreciationValue['goods_life'] = $goodsDepreciationObject->getGoods_Life();
		$depreciationValue['scrap_value'] = $goodsDepreciationObject->getScrap_Value();
		$depreciationValue['remarks'] = $goodsDepreciationObject->getRemarks();
		// Select
		$sql = new Sql($this->dbAdapter);
                $select = $sql->select();

                $select->from ('depreciation_table');
		$select->columns(['item_name_id']);
                $select->where('item_name_id = '.$depreciationValue['item_name_id']);
                $stmt = $sql->prepareStatementForSqlObject($select);
		$resource  = $stmt->execute();
		$result = iterator_to_array ( $resource );
		
		if(count($result) < 1)
		{
			$action = new Insert('depreciation_table');
			$action->values($depreciationValue);
		}
		else
		{
			$action = new Update('depreciation_table');
                        $action->set($depreciationValue);
			$action->where(array('item_name_id = ?' => $depreciationValue['item_name_id']));

		}
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
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
