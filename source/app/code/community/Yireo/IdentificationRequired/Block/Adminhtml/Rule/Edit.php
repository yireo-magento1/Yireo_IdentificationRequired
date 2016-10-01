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
class Yireo_IdentificationRequired_Block_Adminhtml_Rule_Edit extends Yireo_FormApi_Block_Adminhtml_Container
{
    /**
     * XML form file
     *
     * @var string
     */
    protected $form_xml = 'identificationrequired/rule/rule.xml';

    /**
     * XML form name
     *
     * @var string
     */
    protected $form_name = 'ruleForm';

    /**
     * @throws Exception
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('identificationrequired/rule/edit.phtml');

        // Load the rule
        $rule = Mage::getModel('identificationrequired/rule')->load($this->getRequest()->getParam('id', 0));

        // Set rule and form in template
        $this->setRule($rule);
        $this->setFormData($rule->getData());
    }

    /**
     * Return a listing of all products stored within this rule
     */
    public function getProducts()
    {
    }

    /**
     * Return a listing of all categories stored within this rule
     */
    public function getCategories()
    {
    }
}
