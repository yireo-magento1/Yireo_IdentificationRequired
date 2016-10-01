<?php
/**
 * Yireo IdentificationRequired for Magento 
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * Rule model
 */
class Yireo_IdentificationRequired_Model_Rule extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('identificationrequired/rule');
    }

    /**
     * Load a specific rule
     *
     * @param int $ruleId
     * @param null $field
     *
     * @return Mage_Core_Model_Abstract
     */
    public function load($ruleId, $field = null)
    {
        $rt = parent::load($ruleId, $field);

        $storeIds = $this->loadStoreIdsByRule($ruleId);
        $this->setStoreId($storeIds);

        return $rt;
    }

    /**
     * @param $ruleId
     *
     * @return array
     */
    protected function loadStoreIdsByRule($ruleId)
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $tableName = $resource->getTableName('identificationrequired_rule_store');
        $query = 'SELECT rule_id,store_id FROM '.$tableName.' WHERE rule_id = '.(int)$ruleId;
        $results = $readConnection->fetchAll($query);

        $storeIds = array();
        foreach($results as $result) {
            $storeIds[] = $result['store_id'];
        }

        return $storeIds;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterSave()
    {
        $rt = parent::_afterSave();

        $data = $this->getData();
        if ($this->getId() > 0 && isset($data['store_id'])) {
            $storeIds = $data['store_id'];
            if (!is_array($storeIds)) {
                $storeIds = array($storeIds);
            }

            $this->saveStoreIdsByRuleId($this->getId(), $storeIds);
        }

        return $rt;
    }

    /**
     * @param $ruleId
     * @param $storeIds
     */
    protected function saveStoreIdsByRuleId($ruleId, $storeIds)
    {
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $tableName = $resource->getTableName('identificationrequired_rule_store');
        $query = 'DELETE FROM '.$tableName.' WHERE rule_id = '.$this->getId();
        $writeConnection->query($query);

        foreach($storeIds as $storeId) {
            $query = 'INSERT INTO '.$tableName.' SET rule_id = '.(int)$ruleId.', store_id = '.(int)$storeId;
            $writeConnection->query($query);
        }
    }

    /**
     * @return array
     */
    public function getStoreMapping()
    {
        static $mapping = false;

        if ($mapping == false) {
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $tableName = $resource->getTableName('identificationrequired_rule_store');
            $query = 'SELECT rule_id,store_id FROM '.$tableName;
            $results = $readConnection->fetchAll($query);

            $mapping = array();
            foreach($results as $result) {
                $ruleId = $result['rule_id'];

                if (!isset($mapping[$ruleId])) {
                    $mapping[$ruleId] = array();
                }

                $mapping[$ruleId][] = $result['store_id'];
            }
        }

        return $mapping;
    }
}
