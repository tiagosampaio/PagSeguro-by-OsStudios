<?php
/**
 * Os Studios PagSeguro Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   OsStudios
 * @package    OsStudios_PagSeguro
 * @copyright  Copyright (c) 2012 Os Studios (www.osstudios.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Tiago Sampaio <tiago.sampaio@osstudios.com.br>
 */

class OsStudios_PagSeguro_Model_Returns_Types_Transactions_Transaction extends OsStudios_PagSeguro_Model_Returns
{
    
	const TABS = '			';
	
    /**
     * 
     * Paid with Credit Card
     * @var (int)
     */
    const PAYMENT_TYPE_CC = 1;
	const PAYMENT_TYPE_CC_STRING = 'Cartão de Crédito';
    
	const PAYMENT_TYPE_CC_CODE_VISA = 101;
	const PAYMENT_TYPE_CC_CODE_VISA_STRING = 'Cartão de crédito Visa.';
	
	const PAYMENT_TYPE_CC_CODE_MC = 102;
	const PAYMENT_TYPE_CC_CODE_MC_STRING = 'Cartão de crédito MasterCard.';
	
	const PAYMENT_TYPE_CC_CODE_AMEX = 103;
	const PAYMENT_TYPE_CC_CODE_AMEX_STRING = 'Cartão de crédito American Express.';
	
	const PAYMENT_TYPE_CC_CODE_DINERS = 104;
	const PAYMENT_TYPE_CC_CODE_DINERS_STRING = 'Cartão de crédito Diners.';
	
	const PAYMENT_TYPE_CC_CODE_HIPERCARD = 105;
	const PAYMENT_TYPE_CC_CODE_HIPERCARD_STRING = 'Cartão de crédito Hipercard.';
	
	const PAYMENT_TYPE_CC_CODE_AURA = 106;
	const PAYMENT_TYPE_CC_CODE_AURA_STRING = 'Cartão de crédito Aura.';
	
	const PAYMENT_TYPE_CC_CODE_ELO = 107;
	const PAYMENT_TYPE_CC_CODE_ELO_STRING = 'Cartão de crédito Elo.';
	
	const PAYMENT_TYPE_CC_CODE_PLENOCARD = 108;
	const PAYMENT_TYPE_CC_CODE_PLENOCARD_STRING = 'Cartão de crédito PLENOCard.';
	
	const PAYMENT_TYPE_CC_CODE_PERSONALCARD = 109;
	const PAYMENT_TYPE_CC_CODE_PERSONALCARD_STRING = 'Cartão de crédito PersonalCard.';
	
	const PAYMENT_TYPE_CC_CODE_JCB = 110;
	const PAYMENT_TYPE_CC_CODE_JCB_STRING = 'Cartão de crédito JCB.';
	
	const PAYMENT_TYPE_CC_CODE_DISCOVER = 111;
	const PAYMENT_TYPE_CC_CODE_DISCOVER_STRING = 'Cartão de crédito Discover.';
	
	const PAYMENT_TYPE_CC_CODE_BRASILCARD = 112;
	const PAYMENT_TYPE_CC_CODE_BRASILCARD_STRING = 'Cartão de crédito BrasilCard.';
	
	const PAYMENT_TYPE_CC_CODE_FORTBRASIL = 113;
	const PAYMENT_TYPE_CC_CODE_FORTBRASIL_STRING = 'Cartão de crédito FORTBRASIL.';
	
    /**
     * 
     * Paid with Billet
     * @var (int)
     */
    const PAYMENT_TYPE_BILLET = 2;
	const PAYMENT_TYPE_BILLET_STRING = 'Boleto';
    
	const PAYMENT_TYPE_BILLET_CODE_BRADESCO = 201;
	const PAYMENT_TYPE_BILLET_CODE_BRADESCO_STRING = 'Boleto Bradesco.';
	
	const PAYMENT_TYPE_BILLET_CODE_SANTANDER = 202;
	const PAYMENT_TYPE_BILLET_CODE_SANTANDER_STRING = 'Boleto Santander.';
	
    /**
     * 
     * Paid with Online Debit (TEF)
     * @var (int)
     */
    const PAYMENT_TYPE_DEBIT = 3;
	const PAYMENT_TYPE_DEBIT_STRING = 'Pagamento Online';
    
	const PAYMENT_TYPE_DEBIT_CODE_BRADESCO = 301;
	const PAYMENT_TYPE_DEBIT_CODE_BRADESCO_STRING = 'Débito online Bradesco.';
	
	const PAYMENT_TYPE_DEBIT_CODE_ITAU = 302;
	const PAYMENT_TYPE_DEBIT_CODE_ITAU_STRING = 'Débito online Itaú.';
	
	const PAYMENT_TYPE_DEBIT_CODE_UNIBANCO = 303;
	const PAYMENT_TYPE_DEBIT_CODE_UNIBANCO_STRING = 'Débito online Unibanco.';
	
	const PAYMENT_TYPE_DEBIT_CODE_BB = 304;
	const PAYMENT_TYPE_DEBIT_CODE_BB_STRING = 'Débito online Banco do Brasil.';
	
	const PAYMENT_TYPE_DEBIT_CODE_REAL = 305;
	const PAYMENT_TYPE_DEBIT_CODE_REAL_STRING = 'Débito online Banco Real.';
	
	const PAYMENT_TYPE_DEBIT_CODE_BANRISUL = 306;
	const PAYMENT_TYPE_DEBIT_CODE_BANRISUL_STRING = 'Débito online Banrisul.';
	
	const PAYMENT_TYPE_DEBIT_CODE_HSBC = 307;
	const PAYMENT_TYPE_DEBIT_CODE_HSBC_STRING = 'Débito online HSBC.';
	
    /**
     * 
     * Paid with PagSeguro Account Credits
     * @var (int)
     */
    const PAYMENT_TYPE_PAGSEGUROCREDIT = 4;
	const PAYMENT_TYPE_PAGSEGUROCREDIT_STRING = 'Pagamento';
    
	const PAYMENT_TYPE_PAGSEGUROCREDIT_CODE = 401;
	const PAYMENT_TYPE_PAGSEGUROCREDIT_CODE_STRING = 'Saldo PagSeguro.';
	
    /**
     * 
     * Paid with Oi Paggo via Celphones
     * @var (int)
     */
    const PAYMENT_TYPE_OIPAGGO = 5;
    const PAYMENT_TYPE_OIPAGGO_STRING = 'Oi Paggo';
    
    const PAYMENT_TYPE_OIPAGGO_CODE = 501;
    const PAYMENT_TYPE_OIPAGGO_CODE_STRING = 'Oi Paggo.';
    
    /**
     * 
     * Status: Waiting for the payment
     * @var (int)
     */
    const STATUS_WAITING_PAYMENT = 1;
    const STATUS_WAITING_PAYMENT_STRING = 'Aguardando Pagto';
    
    /**
     * 
     * Status: Payment is being analysed
     * @var (int)
     */
    const STATUS_ANALYSIS = 2;
    const STATUS_ANALYSIS_STRING = 'Em Análise';
    
    /**
     * 
     * Status: The transaction was paid
     * @var (int)
     */
    const STATUS_PAID = 3;
    const STATUS_PAID_STRING = 'Aprovado';
    
    /**
     * 
     * Status: The transaction was paid and the money is available to shopper in PagSeguro 
     * @var (int)
     */
    const STATUS_AVAILABLE = 4;
    const STATUS_AVAILABLE_STRING = 'Completo';
    
    /**
     * 
     * Status: A dispute was opened by customer
     * @var (int)
     */
    const STATUS_IN_DISPUTE = 5;
    
    /**
     * 
     * Status: The money was given back to customer
     * @var (int)
     */
    const STATUS_RETURNED = 6;
    
    /**
     * 
     * Status: The transaction was canceled for any reason
     * @var (int)
     */
    const STATUS_CANCELED = 7;
	const STATUS_CANCELED_STRING = 'Cancelado';
    
    
    /**
     * 
     * Handle the transaction type
     * @var (int)
     */
    protected $_transactionType = self::PAGSEGURO_RETURN_TYPE_DEFAULT;
    
    
	/**
	 * Runs before process any transaction
	 */
	protected function _beforeLogTransactionData()
	{
		$this->log($this->__('%s--- Transaction Data. ---', self::TABS));
		
		$this->log($this->__('%sDate: %s.', self::TABS, $this->getDate()));
        $this->log($this->__('%sReference: %s.', self::TABS, $this->getReference()));
        $this->log($this->__('%sCode: %s.', self::TABS, $this->getCode()));
        $this->log($this->__('%sType: %s.', self::TABS, $this->getType()));
        $this->log($this->__('%sPayment Type: %s.', self::TABS, $this->getPaymentMethodType()));
        $this->log($this->__('%sStatus: %s.', self::TABS, $this->getStatus()));
	}
    
    
	/**
	 * Runs before process any transaction
	 */
	protected function _beforeProcessTransaction()
	{
		$this->log($this->__('%s--- Initializing Transaction Process. ---', self::TABS));
	}
	
	
	/**
	 * Runs before process any transaction
	 */
	protected function _afterProcessTransaction()
	{
		$this->log($this->__('%s--- Finishing Transaction Process. ---', self::TABS));
	}
    
    
    /**
     * Sets the transaction type
     * 
     * @param (int) $transactionType
     * @return \OsStudios_PagSeguro_Model_Returns_Types_Transaction 
     */
    public function setTransactionType($transactionType = self::PAGSEGURO_RETURN_TYPE_DEFAULT)
    {
        $this->_transactionType = $transactionType;
        return $this;
    }
    
    
    /**
     * Sets the transaction DATA
     * 
     * @param array $transaction
     * @return OsStudios_PagSeguro_Model_Returns_Types_Transaction 
     */
    public function setTransactionData($transaction = array())
    {
        switch ($this->_transactionType) {
            case self::PAGSEGURO_RETURN_TYPE_API:
            case self::PAGSEGURO_RETURN_TYPE_CONSULT:
            	
                $this->setDate($transaction['date'])
                     ->setReference($transaction['reference'])
                     ->setCode($transaction['code'])
                     ->setType($transaction['type'])
                     ->setStatus($transaction['status'])
                     ->setGrossAmount($transaction['grossAmount'])
                     ->setDiscountAmount($transaction['discountAmount'])
                     ->setFeeAmount($transaction['feeAmount'])
                     ->setNetAmount($transaction['netAmount'])
                     ->setExtraAmount($transaction['extraAmount'])
                     ->setLastEventDate($transaction['lastEventDate'])
                     ;
                
                switch ($transaction['paymentMethod']['type']) {
                    case self::PAYMENT_TYPE_CC:
                        $paymentType = $this->__('Credit Card');
                        break;
                    case self::PAYMENT_TYPE_BILLET:
                        $paymentType = $this->__('Billet');
                        break;
                    case self::PAYMENT_TYPE_DEBIT:
                        $paymentType = $this->__('Online Debit');
                        break;
                    case self::PAYMENT_TYPE_PAGSEGUROCREDIT:
                        $paymentType = $this->__('PagSeguro Credit');
                        break;
                    case self::PAYMENT_TYPE_OIPAGGO:
                        $paymentType = $this->__('Oi Paggo');
                        break;
                    default:
                        $paymentType = $this->__('Not Provided');
                        break;
                }
                
                $this->setPaymentMethodType($paymentType);
                
                break;
            case self::PAGSEGURO_RETURN_TYPE_DEFAULT:
            default:
            	
            	$this->setDate($transaction['DataTransacao'])
                     ->setReference($transaction['Referencia'])
                     ->setCode($transaction['TransacaoID'])
                     ->setPaymentMethodType($transaction['TipoPagamento'])
                     ->setStatus($transaction['StatusTransacao'])
            		;
            		
                break;
        }
        
        //$this->setStatus( self::STATUS_PAID );
        
        return $this;
    }
    
    /**
     * Process the current transaction
     */
    public function processTransaction()
    {
    	
    	$this->_beforeProcessTransaction();
    	
        $order = $this->loadOrderByIncrementId($this->getReference());
        
        if(($order instanceof Mage_Sales_Model_Order) && ($order->hasData())) {
        	
            if(($payment = $order->getPayment())) {
                $methods = array(Mage::getModel('pagseguro/hpp')->getCode());
                if(!in_array($payment->getMethod(), $methods)) {
                    return false;
                }
            } else {
                return false;
            }
            
        	$payment->setPagseguroTransactionId($this->getCode())
		    	->setPagseguroPaymentMethod($this->getPaymentMethodType())
		    	->save();
        	
		$ordersModel = Mage::getModel('pagseguro/returns_orders');
		$ordersModel->setOrder($order);
			
		$this->_beforeLogTransactionData();
			
            switch ($this->getStatus()) {
                case self::STATUS_PAID:
                case self::STATUS_PAID_STRING:
                case self::STATUS_AVAILABLE:
                case self::STATUS_AVAILABLE_STRING:
                    
                    $result = $ordersModel->processOrderApproved()->getResponse();
                    if($result === OsStudios_PagSeguro_Model_Returns_Orders::ORDER_NOTPROCESSED) {
                    	return false;
                    }
                    
                    break;
                case self::STATUS_CANCELED:
                case self::STATUS_CANCELED_STRING:
                case self::STATUS_RETURNED:
                    
                	$result = $ordersModel->processOrderCanceled()->getResponse();
                	if($result === OsStudios_PagSeguro_Model_Returns_Orders::ORDER_NOTPROCESSED) {
                		return false;
                	}
                	
                    break;
                case self::STATUS_WAITING_PAYMENT:
                case self::STATUS_WAITING_PAYMENT_STRING:
                case self::STATUS_ANALYSIS:
                case self::STATUS_ANALYSIS_STRING:
                case self::STATUS_IN_DISPUTE:
                default:
                    
                	$result = $ordersModel->processOrderWaiting()->getResponse();
            		if($result === OsStudios_PagSeguro_Model_Returns_Orders::ORDER_NOTPROCESSED) {
                		return false;
                	}
                	
                    break;
            }
            
            $ordersModel->unsetOrder();
            
            $this->_afterProcessTransaction();
            
            return true;
        }
    }
}
