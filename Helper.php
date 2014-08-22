<?php
/**
 * @package   ImpressPages
 */


/**
 * Created by PhpStorm.
 * User: mangirdas
 * Date: 8/22/14
 * Time: 10:56 AM
 */

namespace Plugin\PayPal;


class Helper
{
    public static function responseAfterPayment($paymentId, $securityCode)
    {
        $payment = Model::getPayment($paymentId);
        $orderUrl = ipRouteUrl('PayPal_status', array('paymentId' => $paymentId, 'securityCode' => $securityCode));
        $response = new \Ip\Response\Redirect($orderUrl);

        if (!empty($payment['successUrl'])) {
            $response = new \Ip\Response\Redirect($payment['successUrl']);
        }
        $response = ipFilter('PayPal_userBackResponse', $response);
        return $response;
    }
}
