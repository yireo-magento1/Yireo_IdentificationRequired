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
 * Rule overview-block
 */
class Yireo_IdentificationRequired_Block_Adminhtml_Rule_Overview extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Constructor
     */
    public function _construct()
    {
        $this->setTemplate('identificationrequired/rule/overview.phtml');

        parent::_construct();

    }

    /**
     * Prepare the layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        // Add the block grid as child
        $this->setChild('grid', $this->getLayout()
            ->createBlock('identificationrequired/adminhtml_rule_overview_grid', 'rule.grid')
            ->setSaveParametersInSession(true)
        );

        return parent::_prepareLayout();
    }

    /**
     * Helper method to return the child grid HTML
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Helper to return the header of this page
     *
     * @return string
     */
    public function getHeader($title = null)
    {
        return 'Identification Rules';
    }

    /**
     * Return the version
     *
     * @return string
     */
    public function getVersion()
    {
        $config = Mage::app()->getConfig()->getModuleConfig('Yireo_IdentificationRequired');
        return (string)$config->version;
    }
}
