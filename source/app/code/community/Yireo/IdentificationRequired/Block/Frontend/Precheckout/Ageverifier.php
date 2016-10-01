<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Precheckout_Ageverifier extends Mage_Core_Block_Template
{
    /**
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('identificationrequired/precheckout/ageverifier.phtml');
    }
}