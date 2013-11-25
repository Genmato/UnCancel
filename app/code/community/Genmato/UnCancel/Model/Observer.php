<?php

class Genmato_UnCancel_Model_Observer
{

    public function addButton($observer)
    {

        if ($observer->getEvent()->getBlock() instanceof Mage_Adminhtml_Block_Widget_Container &&
            $observer->getEvent()->getBlock()->getId() == 'sales_order_view' &&
            $this->getOrder()->getState() == 'canceled'
        ) {

            $observer->getEvent()->getBlock()
                ->addButton('UnCancel', array(
                    'label' => Mage::helper('adminhtml')->__('UnCancel'),
                    'onclick' => 'setLocation(\'' . $this->getUncancelUrl() . '\')',
                ), -1);

        }
    }

    public function getOrder()
    {
        return Mage::registry('sales_order');
    }

    public function getUncancelUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/uncancel', array('id' => $this->getOrder()->getId()));
    }
}