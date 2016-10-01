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
 * IdentificationRequired helper
 */
class Yireo_IdentificationRequired_Helper_Precheckout extends Mage_Core_Helper_Abstract
{
    /**
     * Set the flag to skip the precheckout
     */
    public function setSessionFlag()
    {
        Mage::getSingleton('core/session')->setPrecheckoutDisplayed(true);
    }

    /**
     * Set the flag to show the precheckout again
     */
    public function unsetSessionFlag()
    {
        Mage::getSingleton('core/session')->setPrecheckoutDisplayed(false);
    }

    /**
     * @return mixed|string
     */
    public function getRedirectUrl()
    {
        $redirect = Mage::app()->getRequest()->getParam('uenc');
        if(empty($redirect)) {
            $redirect = base64_encode(Mage::getUrl('checkout/onepage'));
        }

        return $redirect;
    }
}