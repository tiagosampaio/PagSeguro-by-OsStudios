<?php
class OsStudios_PagSeguro_Model_Api extends OsStudios_PagSeguro_Model_Payment
{
    
    protected $_code = self::PAGSEGURO_METHOD_CODE_API;
    protected $_formBlockType = 'pagseguro/api_form';
    protected $_infoBlockType = 'pagseguro/api_info';
    
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = false;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    
    
    protected $_isGateway                   = true;
    protected $_canOrder                    = true;
    
    /**
     * Return the URL to be redirected to when finish purchasing
     * 
     * @return boolean | string
     */
    public function getOrderPlaceRedirectUrl($orderId = null)
    {
        
        Mage::log($this->_getOrderId(), null, '$orderId.log');
        
        if(is_array(($result = $this->_registerOrderInPagSeguro()))) {
            $url = sprintf('%s?code=%s', Mage::getSingleton('pagseguro/data')->getPagSeguroApiRedirectUrl(), $result['code']);
            return $url;
        }
        
        return false;
    }
    
    /**
     * Register the payment in PagSeguro before redirect
     * 
     * @return boolean | array
     */
    protected function _registerOrderInPagSeguro()
    {
        $credentials = Mage::getSingleton('pagseguro/credentials');
        $url = sprintf('%s?email=%s&token=%s', Mage::getSingleton('pagseguro/data')->getPagSeguroApiUrl(), $credentials->getAccountEmail(), $credentials->getToken());
        
        $xml = Mage::getSingleton('pagseguro/api_xml')->setOrder($this->_getOrder())->getXml();
        
        Mage::log($xml->asXml(), null, '$xml.log');
        
        $client = new Zend_Http_Client($url);
        $client->setMethod(Zend_Http_Client::POST)
               ->setHeaders('Content-Type: application/xml; charset=ISO-8859-1')
               ->setRawData($xml->asXML(), 'text/xml');
        
        $request = $client->request();
        $body = new Varien_Simplexml_Config($request->getBody());
        
        $result = $body->getNode()->asArray();
        
        Mage::log($result, null, '$result.log');
        
        if($result['code'] && $result['date']) {
            return $result;
        }
        
        return false;
    }
    
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
    
}