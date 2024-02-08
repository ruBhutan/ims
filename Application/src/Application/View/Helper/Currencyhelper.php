<?php
/*
 * Bhutaneseprogramer @ bhutaneseprogrammer@gmail.com
 * 
 * Helper -- Currency format
 * 
 * convert Number to currency
 * 
 */
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Accounts\Model\CurrencyTable;

class Currencyhelper extends AbstractHelper
{
	protected $currencyTable;
	
	public function __construct(CurrencyTable $currencyTable)
	{
		$this->currencyTable = $currencyTable;
	}
	
	public function __invoke($number)
	{
		$currency_code = $this->currencyTable->getColumn(array('status'=>'1'),'code');
		
		return $currency_code.' '.number_format($number, 3, '.', ',');
	}
}
