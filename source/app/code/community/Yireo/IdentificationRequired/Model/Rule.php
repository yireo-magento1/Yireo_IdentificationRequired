<?php
/**
 * Yireo IdentificationRequired for Magento 
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
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

    public function load($id, $field = null)
    {
        $rt = parent::load($id, $field);

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $tableName = $resource->getTableName('identificationrequired_rule_store');
        $query = 'SELECT rule_id,store_id FROM '.$tableName.' WHERE rule_id = '.$id;
        $results = $readConnection->fetchAll($query);

        $storeIds = array();
        foreach($results as $result) {
            $storeIds[] = $result['store_id'];
        }

        if(empty($_POST)) {
            //print_r($storeIds);
        }

        $this->setStoreId($storeIds);

        return $rt;
    }

    protected function _afterSave()
    {
        $rt = parent::_afterSave();

        $data = $this->getData();
        if ($this->getId() > 0 && isset($data['store_id'])) {
            $storeIds = $data['store_id'];
            if (!is_array($storeIds)) {
                $storeIds = array($storeIds);
            }

            $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
            $tableName = $resource->getTableName('identificationrequired_rule_store');
            $query = 'DELETE FROM '.$tableName.' WHERE rule_id = '.$this->getId();
            $writeConnection->query($query);

            foreach($storeIds as $storeId) {
                $query = 'INSERT INTO '.$tableName.' SET rule_id = '.$this->getId().', store_id = '.$storeId;
                $writeConnection->query($query);
            }
        }

        return $rt;
    }

    public function getMapping()
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
