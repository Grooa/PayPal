<?php
/**
 * @package   ImpressPages
 */



namespace Plugin\PayPal;


class SiteController extends \Ip\Controller
{
    public function pay($paymentId)
    {
        $order = Model::getPayment($paymentId);
        if (!$order) {
            throw new \Ip\Exception('Order ' . $paymentId . ' doesn\'t exist');
        }



        if (!$order['userId'] && ipUser()->loggedIn()) {
            Model::update($paymentId, array('userId' => ipUser()->userId()));
        }

        if ($order['isPaid']) {
            //TODOX
            //display order page
            $answer = 'paid';
        } else {
            //redirect to the payment
            $paypalModel = PayPalModel::instance();

            $data = array(
                'form' => $paypalModel->getPaypalForm($paymentId)
            );

            $answer = ipView('view/page/paymentRedirect.php', $data)->render();
        }


        return $answer;

    }

    public function status($paymentId, $securityCode)
    {
        $order = Model::getPayment($paymentId);
        if (!$order) {
            throw new \Ip\Exception('Unknown order. Id: ' . $paymentId);
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
