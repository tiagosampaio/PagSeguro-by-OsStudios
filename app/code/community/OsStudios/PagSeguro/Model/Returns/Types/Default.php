<?php
/**
 * Os Studios PagSeguro Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   OsStudios
 * @package    OsStudios_PagSeguro
 * @copyright  Copyright (c) 2012 Os Studios (www.osstudios.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Tiago Sampaio <tiago.sampaio@osstudios.com.br>
 */

class OsStudios_PagSeguro_Model_Returns_Types_Default extends OsStudios_PagSeguro_Model_Returns_Types_Abstract
{
	
	/**
	 * Runs before process any return
	 */
	protected function _beforeProcessReturn()
	{
		$this->log($this->__('%s--- Initializing Default Return Process ---', self::TABS));
	}
	
	
	/**
	 * Runs before process any return
	 */
	protected function _afterProcessReturn()
	{
		$this->log($this->__('%s--- Finishing Default Return Process ---', self::TABS));
	}
	
    
    /**
	 * Process return
	 * 
	 * @return OsStudios_PagSeguro_Model_Returns_Types_Default
	 */
	public function processReturn()
	{
		
		$this->_beforeProcessReturn();
		
		$model = Mage::getModel('pagseguro/returns_types_transactions_transaction');
		$model->setTransactionType(self::PAGSEGURO_RETURN_TYPE_DEFAULT)
				  ->setTransactionData($this->getPost())
				  ->processTransaction();
		
		
		$this->_response = self::PAGSEGURO_RETURN_RESPONSE_SUCCESS;
		$this->_success = true;
		
		$this->_afterProcessReturn();
		
		return $this;
	}
    
}
