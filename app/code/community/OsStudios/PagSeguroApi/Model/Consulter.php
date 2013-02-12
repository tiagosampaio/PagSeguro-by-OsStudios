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

class OsStudios_PagSeguroApi_Model_Consulter extends OsStudios_PagSeguroApi_Model_Returns
{

	const PAGSEGURO_CONSULT_RESPONSE_UNAUTHORIZED 	= 'Unauthorized';
	const PAGSEGURO_CONSULT_RESPONSE_AUTHORIZED 	= 'Authorized';
	const PAGSEGURO_CONSULT_RESPONSE_ERROR 			= 'Process Error';
	const PAGSEGURO_CONSULT_RESPONSE_NOT_FOUND 		= 'Not Found';


	/**
	 * Makes the consult by Transaction ID
	 *
	 * @param (string) $transactionId
 	 * 
	 * @return OsStudios_PagSeguroApi_Model_Consulter
	 */
	public function consultByTransactionId($transactionId)
	{
		if($transactionId) {
			$url = $this->getPagSeguroTransactionUrl($transactionId);

			$transaction = 'AF6C3133-32F3-40DF-B690-F5C016A47ADB';
			$url = "https://ws.pagseguro.uol.com.br/v2/transactions/{$transaction}?email=thiko_38@hotmail.com&token=35EA3CABB6F243059A87B8053FB4905D";

			$this->_consult($url);

			return $this;
		}
	}


	/**
	 * Makes the consult by Notification ID
	 *
	 * @param (string) $notificationId
 	 * 
	 * @return OsStudios_PagSeguroApi_Model_Consulter
	 */
	public function consultByNotificationId($notificationId)
	{
		$url = $this->getPagSeguroNotificationUrl($notificationId);

		Mage::log($url, null, '$url.log');

		$this->_consult($url);

		return $this;
	}


	/**
	 * Abstract method that makes the real consult in PagSeguro
	 * but not proccess the result itself
	 *
	 * @param (string) $url
 	 * 
	 * @return 
	 */
	protected function _consult($url)
	{
		$client = new Zend_Http_Client($url);
		$client->setMethod(Zend_Http_Client::GET);
			   //->setParameterGet($this->_params);
		
		$request = $client->request();
		$body = $request->getBody();

		MAge::log($body, null, '$body.log');

		if(!$this->helper()->isXml($body)) {
			return;
		}

		$xml = new Varien_Simplexml_Config($body);
		
		$this->updateSingleTransaction($xml);
	}

}