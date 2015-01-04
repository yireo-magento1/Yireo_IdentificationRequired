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
 * Rule overview grid-block
 */
class Yireo_IdentificationRequired_Block_Adminhtml_Rule_Overview_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rulesGrid');
        $this->setDefaultSort('rule_id');
        $this->setDefaultDir('DESC');
        $this->setRowClickCallback();
        $this->setUseAjax(false);
        //$this->setPagerVisibility(false);
        //$this->setDefaultLimit(0);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareLayout()
    {
        $this->setChild('new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New'),
                    'onclick'   => 'setLocation(\''.$this->getNewUrl().'\')',
                    'class'   => 'task'
                ))
        );

        return parent::_prepareLayout();
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('identificationrequired/rule')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('rule_id', array(
            'header'=> Mage::helper('identificationrequired')->__('Rule ID'),
            'width' => '50px',
            'index' => 'rule_id',
            'type' => 'number',
        ));

        $this->addColumn('label', array(
            'header'=> Mage::helper('identificationrequired')->__('Label'),
            'index' => 'label',
            'type' => 'text',
        ));

        $this->addColumn('product_ids', array(
            'header'=> Mage::helper('identificationrequired')->__('Product IDs'),
            'index' => 'product_ids',
            'type' => 'text',
        ));

        $this->addColumn('category_ids', array(
            'header'=> Mage::helper('identificationrequired')->__('Category IDs'),
            'index' => 'category_ids',
            'type' => 'text',
        ));

        $this->addColumn('actions', array(
            'header'=> Mage::helper('identificationrequired')->__('Action'),
            'type' => 'action',
            'getter'     => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('identificationrequired')->__('Edit'),
                    'url' => array('base'=>'*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
        ));

        return parent::_prepareColumns();
    }

    public function getNewUrl()
    {
        return $this->getUrl('*/*/edit');
    }

    public function getNewButtonHtml()
    {
        return $this->getChildHtml('new_button');
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'store'=>$this->getRequest()->getParam('store'),
            'id'=>$row->getId())
        );
    }

    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();
        $html = $html . $this->getNewButtonHtml();
        return $html;
    }
}
