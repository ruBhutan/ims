<?php

namespace GoodsDepreciation\Mapper;

use GoodsDepreciation\Model\GoodsDepreciation;
use GoodsDepreciation\Model\FixedAsset;


interface GoodsDepreciationMapperInterface
{

	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);


	/**
	 * 
	 * @return array/ GoodsDepreciation[]
	 */
	 
	public function findAllFixedAssets();
        
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Fixed Asset
	 */
	
	public function findFixedAssetDetails($id);


	public function saveDepreciationValue(FixedAsset $goodsDepreciationInterface);


	/**
	 * 
	 * @return array/ GoodsDepreciation[]
	 */
	 
	public function findAllDepreciationValue();
    
	
}