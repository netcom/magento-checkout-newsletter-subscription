<?php
class Quafzi_CheckoutNewsletterSubscription_Model_Observer
{

    public function addCheckbox ($observer)
    {
        if ($observer->getBlock() instanceof Mage_Checkout_Block_Agreements) {
            $html = $observer->getTransport()->getHtml();
            $checkboxHtml = '<li><p class="agree">'
                . '<input id="subscribe_newsletter" name="is_subscribed" checked="checked" value="1" class="checkbox" type="checkbox" />'
                . '<label for="subscribe_newsletter">' . Mage::helper('sales')->__('Subscribe to Newsletter') . '</label>'
                . '</p></li>';
            $html = str_replace('</ol>', $checkboxHtml . '</ol>', $html);
            $observer->getTransport()->setHtml($html);
        }
    }

    public function subscribe ($observer)
    {
        $quote = $observer->getEvent()->getQuote();
        if ($quote->getBillingAddress() && Mage::app()->getRequest()->getParam('is_subscribed', false)) {
            $status = Mage::getModel('newsletter/subscriber')
                ->setImportMode(true)
                ->subscribe($quote->getBillingAddress()->getEmail());
        }
    }

}
