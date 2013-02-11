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

class OsStudios_PagSeguroApi_Model_Payment_Method_Api extends OsStudios_PagSeguro_Model_Payment
{
    
    protected $_code = self::PAGSEGURO_METHOD_CODE_API;
    protected $_formBlockType = 'pagseguroapi/api_form';
    protected $_infoBlockType = 'pagseguroapi/api_info';
    
    protected $_canUseInternal          = true;
    protected $_canUseForMultishipping  = false;
    protected $_canAuthorize            = true;
    
    protected $_isGateway               = true;
    protected $_canOrder                = true;
    
    protected $_resultCodeRegistry      = 'pagseguroapi_result_code';

    /**
     * Return the URL to be redirected to when finish purchasing
     * 
     * @return boolean | string
     */
    public function getOrderPlaceRedirectUrl($orderId = null)
    {
        return Mage::getUrl('pagseguroapi/pay/success');

        $_code = Mage::registry($this->_resultCodeRegistry);

        if($this->_isValidPagSeguroResultCode($_code)) {
            $url = sprintf('%s?code=%s', $this->getConfigData('pagseguro_api_redirect_url'), $_code);
            Mage::unregister($this->_resultCodeRegistry);
            return $url;
        }
        
        return false;
    }
    
    /**
     * Authorize payment abstract method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return OsStudios_PagSeguro_Model_Payment
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        if (!$this->canAuthorize()) {
            Mage::throwException(Mage::helper('payment')->__('Authorize action is not available.'));
        }

        $url = sprintf('%s?email=%s&token=%s', $this->getConfigData('pagseguro_api_url'), $this->getConfigData('account_email'), $this->getConfigData('account_token'));

        $xml = Mage::getSingleton('pagseguroapi/payment_method_api_xml')->setOrder($this->_getOrder())->getXml();

        $client = new Zend_Http_Client($url);
        $client->setMethod(Zend_Http_Client::POST)
               ->setHeaders('Content-Type: application/xml; charset=ISO-8859-1')
               ->setRawData($xml->asXML(), 'text/xml');
        
        $request = $client->request();

        if(!Mage::helper('pagseguro')->isXml($request->getBody())) {
            Mage::log(Mage::helper('pagseguro')->__("When the system tried to authorize with login '%s' and token '%s' got '%s' as result.", $credentials->getAccountEmail(), $credentials->getToken(), $request->getBody()), null, 'osstudios_pagseguro_unauthorized.log');
            Mage::throwException('A problem has occured while trying to authorize the transaction in PagSeguro.');
        }

        $body = new Varien_Simplexml_Config($request->getBody());

        $result = $body->getNode()->asArray();

        Mage::log($xml, null, '$xml.log');
        Mage::log($body, null, '$body.log');
        Mage::throwException('Opa! Agora não...');

        if((!$result['code'] || !$this->_isValidPagSeguroResultCode($result['code'])) || !$result['date']) {
            Mage::throwException(Mage::helper('pagseguro')->__('Your payment could not be processed by PagSeguro.'));
        }

        Mage::register($this->_resultCodeRegistry, $result['code']);

        return $this;
    }

    /**
     * Get Order Object
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _getOrder()
    {
        return Mage::getModel('sales/order')->loadByIncrementId($this->_getOrderId());
    }
    
    /**
     * Order increment ID getter (either real from order or a reserved from quote)
     *
     * @return string
     */
    private function _getOrderId()
    {
        $info = $this->getInfoInstance();

        if ($this->_isPlaceOrder()) {
            return $info->getOrder()->getIncrementId();
        } else {
            if (!$info->getQuote()->getReservedOrderId()) {
                $info->getQuote()->reserveOrderId();
            }
            return $info->getQuote()->getReservedOrderId();
        }
    }
    
    /**
     * Whether current operation is order placement
     *
     * @return bool
     */
    private function _isPlaceOrder()
    {
        $info = $this->getInfoInstance();
        if ($info instanceof Mage_Sales_Model_Quote_Payment) {
            return false;
        } elseif ($info instanceof Mage_Sales_Model_Order_Payment) {
            return true;
        }
    }

    /**
     * Validates the result code from PagSeguro call
     * 
     * @param (string) $code
     *
     * @return bool
     */
    protected function _isValidPagSeguroResultCode($code)
    {
        if($code && (strlen($code) == 32)) {
            return true;
        }

        return false;
    }
    
}