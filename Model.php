<?php
/**
 * @package   ImpressPages
 */


namespace Plugin\PayPal;


class Model
{
    public static function createPayment($paymentData, $userId = null)
    {
        if ($userId == null) {
            $userId = ipUser()->userId();
        }





        $data = array(
            'title' => $paymentData['title'],
            'orderId' => $paymentData['id'],
            'currency' => $paymentData['currency'],
            'price' => $paymentData['price'],
            'userId' => $userId,
            'securityCode' => self::randomString(32),
            'createdAt' => date('Y-m-d H:i:s'),
        );


        $paymentId = ipDb()->insert('paypal', $data);
        return $paymentId;
    }



    public static function getPayment($paymentId)
    {
        $order = ipDb()->selectRow('paypal', '*', array('id' => $paymentId));
        return $order;
    }
    public static function update($paymentId, $data)
    {
        ipDb()->update('paypal', $data, array('id' => $paymentId));
    }


    protected static function randomString($length)
    {
        return substr(sha1(rand()), 0, $length);
    }
}
