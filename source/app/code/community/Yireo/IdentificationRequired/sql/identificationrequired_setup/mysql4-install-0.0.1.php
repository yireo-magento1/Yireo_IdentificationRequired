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
  `field` varchar(255) NOT NUll DEFAULT 0,
  `customer_id` int(11) NOT NUll DEFAULT 0,
  `value` TEXT,
  PRIMARY KEY  (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
