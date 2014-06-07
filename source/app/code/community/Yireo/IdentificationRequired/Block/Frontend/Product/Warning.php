<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Product_Warning extends Mage_Core_Block_Template
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('identificationrequired/product/warning.phtml');
    }

    public function isProductWithIdentificationRequired()
    {
        $product = Mage::registry('current_product');
        return Mage::helper('identificationrequired')->isProductWithIdentificationRequired($product);
    }

    public function getRuleNotices()
    {
        $product = Mage::registry('current_product');
        $rules = Mage::helper('identificationrequired')->getRulesByProduct($product);

        $ruleNotices = array();
        if(!empty($rules)) {
            foreach($rules as $rule) {
                if($rule->getShowProductNotice() == 1) {
                    $products = $rule->getProducts();
                    $ruleNotice = $rule->getProductNotice();
                    if(!empty($products)) $ruleNotice = str_replace('%s', implode(', ', $products), $ruleNotice);
                    if(!empty($products)) $ruleNotice = str_replace('%d', implode(', ', count($products)), $ruleNotice);
                    if(!in_array($ruleNotice, $ruleNotices)) {
                        $ruleNotices[] = $ruleNotice;
                    }
                }
            }
        }
        return $ruleNotices;
    }
}
