<?php
class OsStudios_PagSeguro_Model_Api extends OsStudios_PagSeguro_Model_Payment
{
    
    protected $_code = self::PAGSEGURO_METHOD_CODE_API;
    protected $_formBlockType = 'pagseguro/api_form';
    protected $_infoBlockType = 'pagseguro/api_info';
    
    protected $_canUseInternal          = true;
    protected $_canUseForMultishipping  = false;
    protected $_canAuthorize            = true;
    
    
    protected $_isGateway               = true;
    protected $_canOrder                = true;
    
    protected $_resultCodeRegistry      = 'pagseguro_result_code';

    /**
     * Return the URL to be redirected to when finish purchasing
     * 
     * @return boolean | string
     */
    public function getOrderPlaceRedirectUrl($orderId = null)
    {
        return Mage::getUrl('pagseguro/pay/success');

        $_code = Mage::registry($this->_resultCodeRegistry);

        if($this->_isValidPagSeguroResultCode($_code)) {
            $url = sprintf('%s?code=%s', Mage::getSingleton('pagseguro/data')->getPagSeguroApiRedirectUrl(), $_code);
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

        $credentials = Mage::getSingleton('pagseguro/credentials');
        $url = sprintf('%s?email=%s&token=%s', Mage::getSingleton('pagseguro/data')->getPagSeguroApiUrl(), $credentials->getAccountEmail(), $credentials->getToken());

        $xml = Mage::getSingleton('pagseguro/api_xml')->setOrder($this->_getOrder())->getXml();

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