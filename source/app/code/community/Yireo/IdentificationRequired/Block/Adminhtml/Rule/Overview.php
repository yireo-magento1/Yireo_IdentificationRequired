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
 * Rule overview-block
 */
class Yireo_IdentificationRequired_Block_Adminhtml_Rule_Overview extends Mage_Adminhtml_Block_Widget_Container
{
    public function _construct()
    {
        $this->setTemplate('identificationrequired/rule/overview.phtml');
        parent::_construct();

    }

    protected function _prepareLayout()
    {
        $this->setChild('grid', $this->getLayout()
            ->createBlock('identificationrequired/adminhtml_rule_overview_grid', 'rule.grid')
            ->setSaveParametersInSession(true)
        );
        return parent::_prepareLayout();
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /*
     * Helper to return the header of this page
     */
    public function getHeader($title = null)
    {
        return 'Identification Rules';
    }

    /**
     * Return the version
     *
     * @access public
     * @param null
     * @return string
     */
    public function getVersion()
    {
        $config = Mage::app()->getConfig()->getModuleConfig('Yireo_IdentificationRequired');
        return (string)$config->version;
    }
}
