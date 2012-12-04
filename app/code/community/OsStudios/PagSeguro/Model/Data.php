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

class OsStudios_PagSeguro_Model_Data extends OsStudios_PagSeguro_Model_Abstract
{
   
	public function isReturnApi($post)
	{
		if( isset($post['notificationCode']) && isset($post['notificationType']) ) {
			return true;
		}
	}
	
	public function isReturnDefault($post)
	{
		if( isset($post['VendedorEmail']) 	&& isset($post['TransacaoID']) 		&& isset($post['Referencia']) 	&& isset($post['DataTransacao']) &&
			isset($post['TipoPagamento']) 	&& isset($post['StatusTransacao']) 	&& isset($post['CliNome']) 		&& isset($post['CliEmail']) &&
			isset($post['CliEndereco']) 	&& isset($post['CliCidade']) 		&& isset($post['CliEstado']) 	&& isset($post['CliCEP']) && 
			isset($post['CliTelefone']) 	&& isset($post['NumItens']) 		&& isset($post['Parcelas']) )
		{
			return true;
		}
	}
	
}
