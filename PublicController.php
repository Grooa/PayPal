<?php
/**
 * @package   ImpressPages
 */


namespace Plugin\PayPal;


class PublicController extends \Ip\Controller
{

    public function ipn()
    {
        $paypalModel = PayPalModel::instance();
        $postData = ipRequest()->getPost();
        ipLog()->info('PayPal.ipn: PayPal notification', $postData);
        $paypalModel->processPayPalCallback($postData);
    }

}
