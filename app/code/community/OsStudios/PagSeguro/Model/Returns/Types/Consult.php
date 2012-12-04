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

class OsStudios_PagSeguro_Model_Returns_Types_Consult extends OsStudios_PagSeguro_Model_Returns_Types_Abstract
{
	
    /**
	 * Consult initial date
	 * 
	 * @var (datetime)
	 */
	protected $_initialDate = null;
	
	/**
	 * Consult ending date
	 * 
	 * @var (datetime)
	 */
	protected $_endingDate = null;
    
	
	/**
	 * Runs before process any return
	 */
	protected function _beforeProcessReturn()
	{
		$this->log($this->__('%s--- Initializing Consult Return Process ---', self::TABS));
	}
	
	
	/**
	 * Runs before process any return
	 */
	protected function _afterProcessReturn()
	{
		$this->log($this->__('%s--- Finishing Consult Return Process ---', self::TABS));
	}
	
	
	/**
	 * Sets the initial date to consult transactions
	 * 
	 * @param (mixed) $date
	 */
	public function setDateInitial($date = null)
	{
		$this->_initialDate = $date;
        return $this;
	}
	
	
	/**
     * Returns the initial date to consult in 'YYYY-MM-DDTHH:MM' format
     * 
     * @return (string)
     * @example 2012-06-08T00:00
     */
     public function getDateInitial($formated = true)
     {
     	$daysBefore = $this->getConfigData('return_consult_range');
     	$this->_initialDate = strtotime("-{$daysBefore} days");
     	
		return $formated ? $this->getCoreDate()->date(self::PAGSEGURO_DATE_FORMAT, $this->_initialDate) : $this->_initialDate;
     }
        
     
	/**
	 * Sets the ending date to consult transactions
	 * 
	 * @param (mixed) $date
	 */
	public function setDateEnding($date = null)
	{
		$this->_endingDate = $date;
        return $this;
	}
	
	
	/**
     * Returns the ending date to consult in 'YYYY-MM-DDTHH:MM' format
     * 
     * @return (string)
     * @example 2012-06-08T00:00
     */
	public function getDateEnding($formated = true)
    {
    	if(!$this->_endingDate) {
    		$this->_endingDate = now();
    	}
    	return $formated ? $this->getCoreDate()->date(self::PAGSEGURO_DATE_FORMAT, $this->_endingDate) : $this->_endingDate;
    }
	
    
    /**
	 * Consults and updates the order statuses with PagSeguro
	 * 
	 * @return OsStudios_PagSeguro_Model_Returns_Types_Consult
	 */
	public function processReturn()
	{
		$this->_beforeProcessReturn();
		
		$this->_params['initialDate'] = $this->getDateInitial();
		$this->_params['finalDate'] = $this->getDateEnding();
		$this->_params['page'] = '1';
		$this->_params['maxPageResults'] = '100';
		$this->_params['email'] = $this->getCredentials()->getAccountEmail();
		$this->_params['token'] = $this->getCredentials()->getToken();

		$this->log( $this->__('%sDate Range of Consult: %s to %s.', self::TABS, $this->_params['initialDate'], $this->_params['finalDate']) );
		
		$client = new Zend_Http_Client($this->getPagSeguroTransactionsUrl());
		$client->setMethod(Zend_Http_Client::GET)
			   ->setParameterGet($this->_params);
		
		$request = $client->request();
		$body = $request->getBody();
		
		if($body == self::PAGSEGURO_RETURN_RESPONSE_UNAUTHORIZED) {
			$this->_response = self::PAGSEGURO_RETURN_RESPONSE_UNAUTHORIZED;
			$this->log($this->__('%sResponse from PagSeguro: %s', self::TABS, $this->_response));
			return $this;
		} elseif(!Mage::helper('pagseguro')->isXml($body)) {
			$this->_response = self::PAGSEGURO_RETURN_RESPONSE_ERROR;
			$this->log($this->__('%sResponse from PagSeguro: %s', self::TABS, $this->_response));
			return $this;
		}
		
		$xml = new Varien_Simplexml_Config($body);
		
		$return = $xml->getNode('transactions/transaction');
		
		foreach( $return as $transaction )
		{
			$model = Mage::getModel('pagseguro/returns_types_transactions_transaction');
			$model->setTransactionType(self::PAGSEGURO_RETURN_TYPE_CONSULT)
				  ->setTransactionData($transaction->asArray())
				  ->processTransaction();
		}
		
		$this->_response = self::PAGSEGURO_RETURN_RESPONSE_AUTHORIZED;
		$this->_success = true;
		
		$this->_afterProcessReturn();
		
		return $this;
	}
    
}
