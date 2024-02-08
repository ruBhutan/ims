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

	public function getEmployeeDetailsId($emp_id)
	{
		return $this->goodsDepreciationMapper->getEmployeeDetailsId($emp_id);
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

	public function saveDepreciationValue(FixedAsset $goodsDepreciation) 
	{
		return $this->goodsDepreciationMapper->saveDepreciationValue($goodsDepreciation);
	}


	public function findAllDepreciationValue()
	{
		return $this->goodsDepreciationMapper->findAllDepreciationValue();
	}
        	
}