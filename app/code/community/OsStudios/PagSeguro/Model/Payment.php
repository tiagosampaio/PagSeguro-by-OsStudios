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

abstract class OsStudios_PagSeguro_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    /**
     * 
     * Current order
     * @var Mage_Sales_Model_Order
     */
    protected $_order = null;
    
    /**
     * 
     * Status: Complete
     * @var (string)
     */
    const PAGSEGURO_STATUS_COMPLETE	= 'Completo';
    
    /**
     * 
     * Status: Waiting for Payment
     * @var (string)
     */
    const PAGSEGURO_STATUS_WAITING_PAYMENT = 'Aguardando Pagto';
    
    /**
     * 
     * Status: Approved
     * @var (string)
     */
    const PAGSEGURO_STATUS_APPROVED	= 'Aprovado';
    
    /**
     * 
     * Status: In Analysis
     * @var (string)
     */
    const PAGSEGURO_STATUS_ANALYSING = 'Em Análise';
    
    /**
     * 
     * Status: Canceled
     * @var (string)
     */
    const PAGSEGURO_STATUS_CANCELED = 'Cancelado';
    
    /**
     * 
     * Status: Returned
     * @var (string)
     */
    const PAGSEGURO_STATUS_RETURNED	= 'Devolvido';
    
    
    /**
     *  Return Order
     *
     *  @return	  Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->_order == null) {
        	return false;
        }
        return $this->_order;
    }
	
    
    /**
     * 
     *  Set Current Order
     *
     *  @param Mage_Sales_Model_Order $order
     */
    public function setOrder(Mage_Sales_Model_Order $order)
    {
    	if(!$this->_order)
    	{
    		$this->_order = $order;
    	}
        return $this;
    }
    
	/**
     * 
     * Registry any event/error log.
     * 
     * @return OsStudios_PagSeguro_Model_Payment
     * 
     * @param string $message
     */
    public function log($message)
    {
    	Mage::getModel('pagseguro/data')->log($message);
    	return $this;
    }
}
