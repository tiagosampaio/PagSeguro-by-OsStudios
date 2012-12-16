<?php
class OsStudios_PagSeguro_Model_Api extends OsStudios_PagSeguro_Model_Payment
{
    
    protected $_code = self::PAGSEGURO_METHOD_CODE_API;
    protected $_formBlockType = 'pagseguro/api_form';
    protected $_infoBlockType = 'pagseguro/api_info';
    
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = false;
    protected $_canCapture = true;
    
    public function _construct() {
        parent::_construct();
    }
    
    private function ___requestSendViaXml()
    {
        $url = 'https://ws.pagseguro.uol.com.br/v2/checkout?email=thiko_38@hotmail.com&token=35EA3CABB6F243059A87B8053FB4905D';
        $xml = file_get_contents('../../pagseguro.xml');
        
        $client = new Zend_Http_Client($url);
        $client->setMethod(Zend_Http_Client::POST)
               ->setHeaders('Content-Type: application/xml; charset=ISO-8859-1')
               ->setRawData($xml, 'text/xml');

        $request = $client->request();
        $body = $request->getBody();
        
        Mage::log($body, null, '$body.log');
        
        $result = new Varien_Simplexml_Config($body);
        
        Mage::log($result->getNode()->asArray(), null, '$result.log');
    }
    
}