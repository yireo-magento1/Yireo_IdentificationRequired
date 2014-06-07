<?php
/**
 * Yireo IdentificationRequired for Magento 
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * Rule model
 */
class Yireo_IdentificationRequired_Model_Mysql4_Rule extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('identificationrequired/rule', 'rule_id');
    }
}
