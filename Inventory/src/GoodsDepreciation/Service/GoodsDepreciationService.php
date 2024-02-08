<?php

namespace GoodsDepreciation\Service;

use GoodsDepreciation\Mapper\GoodsDepreciationMapperInterface;
use GoodsDepreciation\Model\GoodsDepreciation;
use GoodsDepreciation\Model\FixedAsset;

class GoodsDepreciationService implements GoodsDepreciationServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $goodsDepreciationMapper;
	
	public function __construct(GoodsDepreciationMapperInterface $goodsDepreciationMapper) {
		$this->goodsDepreciationMapper = $goodsDepreciationMapper;
	}

	public function getUserDetailsId($username)
	{
		return $this->goodsDepreciationMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->goodsDepreciationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->goodsDepreciationMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username)
	{
		return $this->goodsDepreciationMapper->getOrganisationId($username);
	}

	public function findAllFixedAssets()
	{
		return $this->goodsDepreciationMapper->findAllFixedAssets();
	}
        
	public function findFixedAssetDetails($id) 
	{
		return $this->goodsDepreciationMapper->findFixedAssetDetails($id);
	}
	// Check whwather depreciation value has been entered
	public function findAllDepreciatedFixedAssets()
	{
		return $this->goodsDepreciationMapper->findAllDepreciatedFixedAssets();
	}
	public function saveDepreciationValue(FixedAsset $goodsDepreciation) 
	{
		return $this->goodsDepreciationMapper->saveDepreciationValue($goodsDepreciation);
	}


	public function findAllDepreciationValue()
	{
		return $this->goodsDepreciationMapper->findAllDepreciationValue();
	}
        	
}
