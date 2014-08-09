<?php
/**
 * @package   ImpressPages
 */


namespace Plugin\PayPal;


class PublicController extends \Ip\Controller
{

    public function ipn()
    {
        $this->processPayPalNotification();
    }

    public function userBack()
    {
        $this->processPayPalNotification();


    }

    protected function processPayPalNotification()
    {
        $paypalModel = PayPalModel::instance();
        $postData = ipRequest()->getPost();
        ipLog()->info('PayPal.ipn: PayPal notification', $postData);
        $paypalModel->processPayPalCallback($postData);

        $customData = json_decode(ipRequest()->getPost('custom'), true);
        if (empty($customData['paymentId'])) {
            throw new \Ip\Exception("Unknown order ID");
        }
        if (empty($customData['securityCode'])) {
            throw new \Ip\Exception("Unknown order security code");
        }
        $orderUrl = ipRouteUrl('PayPal_status', array('paymentId' => $customData['paymentId'], 'securityCode' => $customData['securityCode']));
        return new \Ip\Response\Redirect($orderUrl);
    }

}
