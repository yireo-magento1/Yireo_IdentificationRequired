<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/** @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('identificationrequired/rule'), 'precheckout_ageverifier', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'LENGTH'    => 1,
        'NULLABLE'  => true,
        'COMMENT'   => 'Show age verifier on precheckout page'
    ));

$installer->endSetup();