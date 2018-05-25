<?php
class Quafzi_CheckoutNewsletterSubscription_Model_Observer
{

    public function addCheckbox ($observer)
    {
        if ($observer->getBlock() instanceof Mage_Checkout_Block_Agreements
            && false === (boolean)(int)Mage::getStoreConfig('advanced/modules_disable_output/Quafzi_CheckoutNewsletterSubscription')
        ) {
            $html = $observer->getTransport()->getHtml();
            $checkboxHtml = '<li><p class="agree">'
                . '<input id="subscribe_newsletter" name="is_subscribed" value="1" class="checkbox" type="checkbox" />'
                . '<label for="subscribe_newsletter">' . Mage::helper('sales')->__('Subscribe to Newsletter') . '</label>'
                . '</p></li>';
            $html = str_replace('</ol>', $checkboxHtml . '</ol>', $html);
            $observer->getTransport()->setHtml($html);
        }
    }

    public function subscribe($observer)
    {
        $quote = $observer->getEvent()->getQuote();
        if ($quote->getBillingAddress() && Mage::app()->getRequest()->getParam('is_subscribed', false)) {
			//Mage::log($quote->getBillingAddress()->getEmail(), null, 'checkout_subscribe.log');
            //works with aw advancednewsletter
			$subscriber = Mage::getModel('advancednewsletter/subscriber')/*->loadByEmail($_POST['email'])*/;
			try{
				$subscriber->subscribe($quote->getBillingAddress()->getEmail(), array('default'),array());
			} catch( Exception $e)
			{
				Mage::logException($e);
			}
			/*
            $status = Mage::getModel('newsletter/subscriber')
                ->setImportMode(true)
                ->subscribe($quote->getBillingAddress()->getEmail());
			*/
        }
    }

}
