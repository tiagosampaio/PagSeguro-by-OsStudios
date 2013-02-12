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
		
		$request = $client->request();
		$body = $request->getBody();

		if(!$this->helper()->isXml($body)) {
			return;
		}

		$xml = new Varien_Simplexml_Config($body);
		
		$this->updateSingleTransaction($xml);
	}

}