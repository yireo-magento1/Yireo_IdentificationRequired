<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('identificationrequired_value')} (
  `value_id` int(11) NOT NULL auto_increment,
  `field` varchar(255) NOT NULL DEFAULT 0,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `value` TEXT,
  PRIMARY KEY  (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('identificationrequired_rule')} (
  `rule_id` int(11) NOT NULL auto_increment,
  `label` varchar(255) NOT NULL DEFAULT '',
  `minimum_age` int(11) NOT NULL DEFAULT 0,
  `show_product_notice` int(1) NOT NULL DEFAULT 0,
  `product_notice` text,
  `show_checkout_notice` int(1) NOT NULL DEFAULT 0,
  `checkout_notice` text,
  `use_precheckout` int(1) NOT NULL DEFAULT 0,
  `precheckout_notice` text,
  `category_ids` text,
  `product_ids` text,
  `agreement_page` varchar(255),
  `enabled` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('identificationrequired_rule_store')} (
  `rule_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
