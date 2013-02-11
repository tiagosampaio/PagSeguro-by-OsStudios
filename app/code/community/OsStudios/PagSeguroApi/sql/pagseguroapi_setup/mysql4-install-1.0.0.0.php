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

$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS `{$this->getTable('pagseguroapi/payment_history')}`;
	CREATE  TABLE IF NOT EXISTS `{$this->getTable('pagseguroapi/payment_history')}` (
	  `history_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `order_id` INT(10) NOT NULL ,
	  `order_increment_id` INT(10) NOT NULL ,
	  `pagseguro_transaction_id` VARCHAR(32) NULL ,
	  `pagseguro_transaction_date` DATETIME NULL ,
	  `pagseguro_payment_method_type` INT UNSIGNED NULL ,
	  `pagseguro_payment_method_code` INT UNSIGNED NULL ,
	  `times_redirected` INT UNSIGNED DEFAULT 0 ,
	  `created_at` DATETIME NULL ,
	  `updated_at` DATETIME NULL ,
	  PRIMARY KEY (`history_id`) )
	ENGINE = MyISAM
	DEFAULT CHARACTER SET = utf8
	COLLATE = utf8_general_ci
");

$installer->endSetup();