<?php
/**
 * Os Studios PagSeguro Api Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   OsStudios
 * @package    OsStudios_PagSeguroApi
 * @copyright  Copyright (c) 2013 Os Studios (www.osstudios.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Tiago Sampaio <tiago.sampaio@osstudios.com.br>
 */

/**
 * PagSeguro Api Payment Installments Block
 *
 */

class OsStudios_PagSeguroApi_Block_Api_Installments extends Mage_Core_Block_Template
{

	protected $_creditCards = null;
	protected $_factors = null;

	public function _construct()
	{
		$this->setTemplate('osstudios/pagseguroapi/installments.phtml');
		return parent::_construct();
	}

	public function getQuote()
	{
		return Mage::getSingleton('checkout/session')->getQuote();
	}

	public function getFactors()
	{
		if(!$this->_factors) {
			$this->_factors = Mage::app()->getConfig()->getNode('default/osstudios_pagseguroapi/transaction/installments/factors')->asArray();
		}

		return $this->_factors;
	}

	public function getCreditCards()
	{
		if(!$this->_creditCards) {
			$this->_creditCards = Mage::app()->getConfig()->getNode('default/osstudios_pagseguroapi/transaction/installments/credit_cards')->asArray();
		}

		return $this->_creditCards;
	}

}