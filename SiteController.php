<?php
/**
 * @package   ImpressPages
 */



namespace Plugin\PayPal;


class SiteController extends \Ip\Controller
{
    public function pay($orderId)
    {
        $order = Model::getOrder($orderId);
        if (!$order) {
            throw new \Ip\Exception('Order ' . $orderId . ' doesn\'t exist');
        }



        if (!$order['userId'] && ipUser()->loggedIn()) {
            Model::update($orderId, array('userId' => ipUser()->userId()));
        }

        if ($order['isPaid']) {
            //TODOX
            //display order page
            $answer = 'paid';
        } else {
            //redirect to the payment
            $paypalModel = PayPalModel::instance();

            $data = array(
                'form' => $paypalModel->getPaypalForm($orderId)
            );

            $answer = ipView('view/page/paymentRedirect.php', $data)->render();
        }


        return $answer;

    }

    public function status($orderId, $securityCode)
    {
        $order = Model::getOrder($orderId);
        if (!$order) {
            throw new \Ip\Exception('Unknown order. Id: ' . $orderId);
        }
        if ($order['securityCode'] != $securityCode) {
            throw new \Ip\Exception('Incorrect order security code');
        }

        $data = array(
            'order' => $order
        );
        $view = ipView('view/page/status.php', $data);
        return $view;
    }
}
