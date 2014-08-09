<?php
/**
 * @package   ImpressPages
 */


namespace Plugin\PayPal;


class Model
{
    public static function createOrder($paymentData, $userId = null)
    {
        if ($userId == null) {
            $userId = ipUser()->userId();
        }





        $data = array(
            'item' => $paymentData['item'],
            'currency' => $paymentData['currency'],
            'price' => $paymentData['price'],
            'userId' => $userId,
            'securityCode' => self::randomString(32),
            'createdAt' => date('Y-m-d H:i:s'),
        );


        $orderId = ipDb()->insert('paypal', $data);
        return $orderId;
    }



    public static function getOrder($orderId)
    {
        $order = ipDb()->selectRow('paypal', '*', array('id' => $orderId));
        return $order;
    }
    public static function update($orderId, $data)
    {
        ipDb()->update('paypal', $data, array('id' => $orderId));
    }


    protected static function randomString($length)
    {
        return substr(sha1(rand()), 0, $length);
    }
}
