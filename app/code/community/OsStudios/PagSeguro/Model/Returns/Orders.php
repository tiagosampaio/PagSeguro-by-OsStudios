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

class OsStudios_PagSeguro_Model_Returns_Orders extends OsStudios_PagSeguro_Model_Abstract
{
   
	const TABS = '				';
	
	const ORDER_NOTPROCESSED = 0;
	const ORDER_PROCESSED = 1;
	
	protected $_order = null;
	protected $_response = self::ORDER_NOTPROCESSED;
	
	public function getResponse()
	{
		return $this->_response;
	}
	
	
	/**
	 * Runs before process any order
	 */
	protected function _beforeProcessOrder()
	{
		$this->log($this->__('%s--- Initializing order process ---', self::TABS));
	}
	
	
	/**
	 * Runs before process any order
	 */
	protected function _afterProcessOrder()
	{
		$this->log($this->__('%s--- Finishing order process ---', self::TABS));
	}
	
	
	/**
	 * Set current Order
	 * 
	 * @param Mage_Sales_Model_Order $order
	 * @return OsStudios_PagSeguro_Model_Orders
	 */
	public function setOrder(Mage_Sales_Model_Order $order)
	{
		$this->_order = $order;
		return $this;
	}
	
	
	/**
	 * Unset current order
	 * 
	 * @return OsStudios_PagSeguro_Model_Orders
	 */
	public function unsetOrder()
	{
		$this->_order = null;
		return $this;
	}
	
	
	/**
	 * Process canceled orders
	 * 
	 * @return OsStudios_PagSeguro_Model_Orders
	 */
	public function processOrderCanceled()
	{
		
		$this->_beforeProcessOrder();
		
		if(!$this->_order) {
			return $this;
		}
		
		$order = $this->_order;
		
		if ($order->canUnhold()) {
			$order->unhold();
			$this->log($this->__('%sThe order was unholded.', self::TABS));
		}
                    
		if($this->_order->canCancel()) {
            $state = $this->getConfigData('canceled_orders_change_to');
            $status = $this->getConfigData('canceled_orders_change_to');
            $comment = $this->__('Order was canceled by PagSeguro.');
                        
            $order->getPayment()->setMessage($comment)->save();
            $order->setState($state, $status, $comment, true)->save();
			$order->cancel();
			
			$this->log($this->__('%sOrder was found in history: #%s', self::TABS, $order->getRealOrderId()));
            $this->log($this->__('%sThe order was successfully processed.', self::TABS));
            $this->log($this->__('%sNew status: %s.', self::TABS, $status));
			
			$this->_response = self::ORDER_PROCESSED;
		} else {
			$this->log($this->__('%sThe order was not processed.', self::TABS));
			$this->_response = self::ORDER_NOTPROCESSED;
		}
		
		$this->_afterProcessOrder();
		
		return $this;
	}
	
	
	/**
	 * Process approved orders
	 * 
	 * @return OsStudios_PagSeguro_Model_Orders
	 */
	public function processOrderApproved()
	{
		
		$this->_beforeProcessOrder();
		
		if(!$this->_order) {
			return $this;
		}
		
		$order = $this->_order;
		
		if($order->canUnhold()) {
        	$order->unhold();
        	$this->log($this->__('%sThe order was unholded.', self::TABS));
		}
                    
		if($order->canInvoice()) {
                        
            $state = $this->getConfigData('paid_orders_change_to');
            $status = $this->getConfigData('paid_orders_change_to');
            $comment = $this->__('Payment confirmed by PagSeguro (%s). PagSeguro Transaction: %s.', $this->getPaymentMethodType(), $this->getCode()) ;
            $notify = true;
            $visibleOnFront = true;
                        
            $invoice = $order->prepareInvoice();
            $invoice->register()->pay();
            $invoice->addComment($comment, $notify, $visibleOnFront)->save();
            $invoice->sendUpdateEmail($visibleOnFront, $comment);
            $invoice->setEmailSent(true);
                        
            Mage::getModel('core/resource_transaction')->addObject($invoice)
                                                       ->addObject($invoice->getOrder())
                                                       ->save();
                        
            $comment = $this->__('Invoice #%s was created.', $invoice->getIncrementId());
            $order->setState($state, $status, $comment, true)->save();
			
            $this->log($this->__('%sOrder was found in history: #%s', self::TABS, $order->getRealOrderId()));
            $this->log($this->__('%sThe order was successfully processed.', self::TABS));
            $this->log($this->__('%sNew status: %s.', self::TABS, $status));
            
            $this->_response = self::ORDER_PROCESSED;
		} else {
			$this->log($this->__('%sThe order was not processed.', self::TABS));
			$this->_response = self::ORDER_NOTPROCESSED;
		}
		
		$this->_afterProcessOrder();
		
		return $this;
	}
	
	
	/**
	 * Process approved orders
	 * 
	 * @return OsStudios_PagSeguro_Model_Orders
	 */
	public function processOrderWaiting()
	{
		
		$this->_beforeProcessOrder();
		
		if(!$this->_order) {
			return $this;
		}
		
		$order = $this->_order;
		
		if($order->canHold()) {
			$order->hold()->save();
			
			$this->log($this->__('%sOrder was found in history: #%s', self::TABS, $order->getRealOrderId()));
            $this->log($this->__('%sThe order was successfully holded.', self::TABS));
			
			$this->_response = self::ORDER_PROCESSED;
		} else {
			$this->log($this->__('%sThe order was not processed.', self::TABS));
			$this->_response = self::ORDER_NOTPROCESSED;
		}
		
		$this->_afterProcessOrder();
		
		return $this;
	}
}
