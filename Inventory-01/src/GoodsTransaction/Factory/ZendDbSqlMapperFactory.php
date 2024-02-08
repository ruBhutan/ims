<?php

namespace GoodsTransaction\Factory;

use GoodsTransaction\Mapper\ZendDbSqlMapper;
use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\ItemCategory;
use GoodsTransaction\Model\ItemSubCategory;
use GoodsTransaction\Model\ItemQuantityType;
use GoodsTransaction\Model\ItemName;
use GoodsTransaction\Model\ItemSupplier;
use GoodsTransaction\Model\ItemDonar;
use GoodsTransaction\Model\GoodsReceived;
use GoodsTransaction\Model\Itemreceivedpurchased;
use GoodsTransaction\Model\IssueGoods;
use GoodsTransaction\Model\DeptGoods;
use GoodsTransaction\Model\DeptIssueGoods;
use GoodsTransaction\Model\GoodsSurrender;
use GoodsTransaction\Model\GoodsTransfer;
use GoodsTransaction\Model\DeptGoodsSurrender;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZendDbSqlMapperFactory implements FactoryInterface
{
	/*
	* Create Service
	* @ param ServiceLocatorInterface $serviceLocator
	* @ return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ZendDbSqlMapper(
			$serviceLocator->get('Zend\Db\Adapter\Adapter'),
			new ClassMethods(false),
			//new \stdClass(),
			new GoodsTransaction()
		);
	}	
}