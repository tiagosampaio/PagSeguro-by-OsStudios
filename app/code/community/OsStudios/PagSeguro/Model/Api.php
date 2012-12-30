<?php
class OsStudios_PagSeguro_Model_Api extends OsStudios_PagSeguro_Model_Payment
{
    
    protected $_code = self::PAGSEGURO_METHOD_CODE_API;
    protected $_formBlockType = 'pagseguro/api_form';
    protected $_infoBlockType = 'pagseguro/api_info';
    
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = false;
    protected $_canCapture = true;
    
    /**
     * Return the URL to be redirected to when finish purchasing
     * 
     * @return boolean | string
     */
    public function getOrderPlaceRedirectUrl()
    {
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
        
        $xml = Mage::getSingleton('pagseguro/api_xml')->getXml();
        
        Mage::log($xml->asXML(), null, '$xml.log');
        Mage::log($xml->asXML(), null, '$xml.log');
        
        $client = new Zend_Http_Client($url);
        $client->setMethod(Zend_Http_Client::POST)
               ->setHeaders('Content-Type: application/xml; charset=ISO-8859-1')
               ->setRawData($xml->asXML(), 'text/xml');
        
        $request = $client->request();
        $body = new Varien_Simplexml_Config($request->getBody());
        
        $result = $body->getNode()->asArray();
        
        if($result['code'] && $result['date']) {
            return $result;
        }
        
        return false;
    }
    
}