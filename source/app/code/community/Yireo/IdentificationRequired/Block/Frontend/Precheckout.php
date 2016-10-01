<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Precheckout extends Mage_Core_Block_Template
{
    /**
     * @var bool
     */
    protected $showAgeVerifier = false;

    /**
     * @var array
     */
    protected $applicableRules = array();

    /**
     * @var Yireo_IdentificationRequired_Helper_Precheckout
     */
    protected $precheckoutHelper;

    /**
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();

        $this->precheckoutHelper = Mage::helper('identificationrequired/precheckout');
        $this->setTemplate('identificationrequired/precheckout.phtml');
    }

    /**
     * @return string
     */
    public function getSaveUrl()
    {
        return Mage::getUrl('identificationrequired/precheckout/save');
    }

    /**
     * @return mixed|string
     */
    public function getRedirectUrl()
    {
        return $this->precheckoutHelper->getRedirectUrl();
    }

    /**
     *
     */
    protected function loadApplicableRules()
    {
        $cartItems = Mage::helper('identificationrequired')->getCartItemsAsArray();

        $rules = Mage::helper('identificationrequired')->getRulesCollection();
        if(!empty($rules)) {
            /** @var Yireo_IdentificationRequired_Model_Rule $rule */
            foreach($rules as $rule) {
                if($rule->getUsePrecheckout() == 1) {

                    $matches = array();

                    foreach($rule->getProductIds() as $productId) {
                        if(array_key_exists($productId, $cartItems)) {
                            $matches[$productId] = $cartItems[$productId];
                        }

                        if($productId == '*') {
                            foreach($cartItems as $cartItemProductId => $cartItem) {
                                $matches[$cartItemProductId] = $cartItem;
                            }
                        }
                    }

                    foreach($rule->getCategoryIds() as $categoryId) {
                        foreach($cartItems as $cartItem) {
                            if(in_array($categoryId, $cartItem->getCategoryIds())) {
                                $matches[$cartItem->getId()] = $cartItem;
                            }
                        }

                        if($categoryId == '*') {
                            foreach($cartItems as $cartItemProductId => $cartItem) {
                                $matches[$cartItemProductId] = $cartItem;
                            }
                        }
                    }

                    if(!empty($matches)) {
                        $rule->setApplicableProducts($matches);
                        $rule->setAgreementText(Mage::helper('formapi/content')->getCmspageContents($rule->getAgreementPage()));

                        if ($rule->getPrecheckoutAgeverifier() == 1) {
                            $this->showAgeVerifier = true;
                        }

                        $this->applicableRules[] = $rule;
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getApplicableRules()
    {
        if (empty($this->applicableRules)) {
            $this->loadApplicableRules();
        }

        return $this->applicableRules;
    }

    /**
     * @return bool
     */
    public function showAgeVerifier()
    {
        if (empty($this->applicableRules)) {
            $this->loadApplicableRules();
        }

        return $this->showAgeVerifier;
    }
}
