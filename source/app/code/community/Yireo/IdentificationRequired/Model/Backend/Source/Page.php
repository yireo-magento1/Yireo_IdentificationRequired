<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Model_Backend_Source_Page
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = Mage::getModel('cms/page')->getCollection();

        $return = array();
        foreach($collection as $item) {
            $return[] = array(
                'value' => $item->getPageId(),
                'label' => $item->getTitle(),
             );
        }

        return $return;
    }

}
