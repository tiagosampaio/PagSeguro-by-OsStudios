<?php
class OsStudios_PagSeguro_Model_Api_Xml extends OsStudios_PagSeguro_Model_Abstract
{
    
    protected $_quote = null;
    protected $_order = null;
    protected $_xml = null;
    
    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
        if($quote->getId()) {
            $this->_quote = $quote;
        }
        
        return $this;
    }
    
    public function getQuote()
    {
        if(!$this->_quote) {
            $this->_quote = Mage::getSingleton('checkout/session')->getQuote();
        }
        return $this->_quote;
    }
    
    public function getXml()
    {
        if(!$this->_xml) {
            $this->_getBaseXml();
        }
        
        return $this->_xml;
    }
    
    protected function _setBaseXml(SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
        return $this;
    }
    
    protected function _getBaseXml()
    {
        //$xml = new SimpleXMLElement('<xml/>', $options, $data_is_url, $ns, $is_prefix);
        $xml = new SimpleXMLElement('<checkout/>');
        $this->_setBaseXml($xml);
        
        $this->_getNodeCurrency()
             ->_getNodeItems()
             ->_getNodeReference()
             ->_getNodeSender()
             ->_getNodeShipping();
        
        return $xml;
    }
    
    protected function _getNodeCurrency()
    {
        if($this->getQuote()) {
            $this->_xml->addChild('currency', $this->getQuote()->getStoreCurrencyCode());
        }
        return $this;
    }
    
    protected function _getNodeItems()
    {
        
        $xmlItems = $this->_xml->addChild('items');
        
        if($this->getQuote()) {
            foreach($this->getQuote()->getAllItems() as $item) {
                $xmlItem = $xmlItems->addChild('item');

                $xmlItem->addChild('id', $item->getProductId());
                $xmlItem->addChild('description', $item->getName());
                $xmlItem->addChild('amount', $item->getRowTotal());
                $xmlItem->addChild('quantity', $item->getQty());
                $xmlItem->addChild('weight', $item->getWeight());
            }
        }
        
        return $this;
    }
    
    protected function _getNodeReference()
    {
        if($this->getQuote()) {
            $this->_xml->addChild('reference', $this->getQuote()->getReservedOrderId());
        }
        return $this;
    }
    
    protected function _getNodeSender()
    {
        $xmlSender = $this->_xml->addChild('sender');
        
        if($this->getQuote()) {
            $xmlSender->addChild('name', $this->getQuote()->getCustomerFirstname() . ' ' . $this->getQuote()->getCustomerLastname());
            $xmlSender->addChild('email', $this->getQuote()->getCustomerEmail());
            
            /**
             * @todo: Find another way to treat the phone number.
             * 
             */
            $phone = preg_replace('/[^0-9]/', null, $this->getQuote()->getShippingAddress()->getTelephone());
            
            $digitCount = 8;
            if(($len = strlen($phone)) >= 11) {
                $digitCount = 9;
            } elseif($len == 10) {
                $digitCount = 8;
            }
            
            $areaCode = substr($phone, 0, ($len-$digitCount));
            $number = substr($phone, ($len-$digitCount), $digitCount);
            
            $xmlPhone = $xmlSender->addChild('phone');
            $xmlPhone->addChild('areaCode', $areaCode);
            $xmlPhone->addChild('number', $number);
        }
        
        return $this;
    }
    
    protected function _getNodeShipping()
    {
        $xmlShipping = $this->_xml->addChild('shipping');
        
        if($this->getQuote()) {
            $xmlShipping->addChild('type', 1);
            $xmlAddress = $xmlShipping->addChild('address');
            
            $shipping = $this->getQuote()->getShippingAddress();
            
            if(is_array($shipping->getStreet())) {
                $street = implode(' - ', $shipping->getStreet());
            } elseif(is_string($shipping->getStreet())) {
                $street = $shipping->getStreet();
            }
            
            $address = Mage::helper('pagseguro/visie')->trataEndereco($street);
            
            $xmlAddress->addChild('street', preg_replace('/[^0-9]/', null, $address[0]));
            $xmlAddress->addChild('number', $address[1]);
            $xmlAddress->addChild('complement');
            $xmlAddress->addChild('district', $address[2]);
            $xmlAddress->addChild('postalCode', preg_replace('/[^0-9]/', null, $shipping->getPostcode()));
            $xmlAddress->addChild('city', $shipping->getCity());
            
            $xmlAddress->addChild('state', $shipping->getRegion());
            $xmlAddress->addChild('country', $shipping->getCountryId());
        }
        
        return $this;
    }
    
}