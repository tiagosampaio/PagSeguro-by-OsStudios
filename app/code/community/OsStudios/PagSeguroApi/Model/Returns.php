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

class OsStudios_PagSeguroApi_Model_Returns extends OsStudios_PagSeguroApi_Model_Abstract
{

	/**
	 * Updates a single transaction by return or by consulting process
	 *
	 * @param Varien_Simplexml_Config $xml
	 *
	 * @return (boolean)
	 */
	public function updateSingleTransaction(Varien_Simplexml_Config $xml)
	{
		$transaction = Mage::getModel('pagseguroapi/returns_transaction');

		if($this->_isTransactionCompatible($transaction->importData($xml))) {
			$this->_updatePaymentHistory($transaction);
			//Mage::log($transaction->debug(), null, '$transaction.log');
		}

	}

	protected function _updatePaymentHistory(OsStudios_PagSeguroApi_Model_Returns_Transaction $transaction, $forceUpdate = true)
	{
		$history = Mage::getModel('pagseguroapi/payment_history')->load($transaction->getOrder()->getEntityId(), 'order_id');

		//Mage::log($history->debug(), null, '$history.log');

		if($history->getHistoryId()) {
			$history->setPagseguroTransactionId($transaction->getCode())
					->setPagseguroTransactionStatus($transaction->getStatus())
					->setPagseguroPaymentMethodType($transaction->getPaymentMethod()->getType())
					->setPagseguroPaymentMethodCode($transaction->getPaymentMethod()->getCode())
					->setUpdatedAt(now());

			$history->save();
		}
	}

	/**
	 * Verifies if the transaction passed is compatible with the order in the system.
	 *
	 * @param OsStudios_PagSeguroApi_Model_Returns_Transaction $transaction
	 *
	 * @return (boolean)
	 */
	private function _isTransactionCompatible(OsStudios_PagSeguroApi_Model_Returns_Transaction $transaction)
	{
		if(!$transaction->getOrder() || !($transaction->getOrder() instanceof Mage_Sales_Model_Order)) {
			return false;
		} elseif(((float) $transaction->getOrder()->getGrandTotal() - (float) $transaction->getGrossAmount()) > 0) {
			return false;
		} elseif($transaction->getOrder()->getCustomerEmail() !== $transaction->getSender()->getEmail()) {
			return false;
		} elseif((int) count($transaction->getOrder()->getAllVisibleItems()) !== (int) $transaction->getItemCount()) {
			return false;
		}

		return true;
	}

}