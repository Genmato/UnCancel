<?php

class Genmato_UnCancel_Model_Uncancel extends Mage_Core_Model_Abstract
{

    public function order($Id)
    {

        $order = Mage::getModel('sales/order')->load($Id);
        if ($order->getId()) {
            $order->setData('state', 'processing')
                ->setData('status', 'processing')
                ->setData('base_discount_canceled', 0)
                ->setData('base_shipping_canceled', 0)
                ->setData('base_subtotal_canceled', 0)
                ->setData('base_tax_canceled', 0)
                ->setData('base_total_canceled', 0)
                ->setData('discount_canceled', 0)
                ->setData('shipping_canceled', 0)
                ->setData('subtotal_canceled', 0)
                ->setData('tax_canceled', 0)
                ->setData('total_canceled', 0);

            $items = $order->getItemsCollection();
            $productUpdates = array();
            foreach ($items as $item) {
                $canceled = $item->getQtyCanceled();
                if ($canceled > 0) {
                    $productUpdates[$item->getProductId()] = array('qty' => $canceled);
                }
                $item->setData('qty_canceled', 0);

            }
            try {
                Mage::getSingleton('cataloginventory/stock')->registerProductsSale($productUpdates);
                $items->save();
                $currentState = $order->getState();
                $currentStatus = $order->getStatus();

                $order->setState($currentState, $currentStatus, Mage::helper('adminhtml')->__('Order uncanceled'), false)->save();
                $order->save();
            } catch (Exception $ex) {
                Mage::log('Error uncancel order: ' . $ex->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }
}