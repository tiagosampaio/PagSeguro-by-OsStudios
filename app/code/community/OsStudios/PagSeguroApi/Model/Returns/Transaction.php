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

class OsStudios_PagSeguroApi_Model_Returns_Transaction extends OsStudios_PagSeguroApi_Model_Returns
{

	public function importData(Varien_Simplexml_Config $xml)
	{
		$arr = $xml->getNode()->asArray();

		$paymentMethod = new Varien_Object();
		$paymentMethod->setType($arr['paymentMethod']['type'])
					  ->setCode($arr['paymentMethod']['code']);

		$sender = new Varien_Object();
		$sender->setName($arr['sender']['name'])
			   ->setEmail($arr['sender']['email']);

		$data = array(
			'date' 					=> $arr['date'],
			'code' 					=> $arr['code'],
			'reference' 			=> $arr['reference'],
			'type' 					=> $arr['type'],
			'status' 				=> $arr['status'],
			'last_event_date' 		=> $arr['lastEventDate'],
			'payment_method' 		=> $paymentMethod,
			'payment_method_type'	=> $paymentMethod->getType(),
			'payment_method_code'	=> $paymentMethod->getCode(),
			'gross_amount' 			=> $arr['grossAmount'],
			'discount_amount' 		=> $arr['discountAmount'],
			'fee_amount' 			=> $arr['feeAmount'],
			'net_amount' 			=> $arr['netAmount'],
			'extra_amount' 			=> $arr['extraAmount'],
			'installment_count' 	=> $arr['installmentCount'],
			'item_count' 			=> $arr['itemCount'],
			'sender' 				=> $sender,
			'sender_name' 			=> $sender->getName(),
			'sender_email' 			=> $sender->getEmail(),
		);

		if($data['reference']) {
			$order = Mage::getModel('sales/order')->loadByIncrementId($data['reference']);
			if($order->getId()) {
				$this->setOrder($order);
			}
		}

		$this->addData($data);

		return $this;
	}

}