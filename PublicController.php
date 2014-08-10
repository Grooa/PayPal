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
        //PayPal cares just to get HTTP 200. So no response is needed.
    }

    public function userBack()
    {
        $this->processPayPalNotification();

        $customData = json_decode(ipRequest()->getPost('custom'), true);
        if (empty($customData['paymentId'])) {
            throw new \Ip\Exception("Unknown order ID");
        }
        if (empty($customData['securityCode'])) {
            throw new \Ip\Exception("Unknown order security code");
        }
        $orderUrl = ipRouteUrl('PayPal_status', array('paymentId' => $customData['paymentId'], 'securityCode' => $customData['securityCode']));

        $response = new \Ip\Response\Redirect($orderUrl);

        $payment = Model::getPayment($customData['paymentId']);

        if (!empty($payment['successUrl'])) {
            $response = new \Ip\Response\Redirect($payment['successUrl']);
        }

        $response = ipFilter('PayPal_userBackResponse', $response);
        return $response;
    }

    protected function processPayPalNotification()
    {
        $paypalModel = PayPalModel::instance();
        $postData = ipRequest()->getPost();
        ipLog()->info('PayPal.ipn: PayPal notification', $postData);
        $paypalModel->processPayPalCallback($postData);
    }

}
