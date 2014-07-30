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

        $paypalModel = PayPalModel::instance();

        $data = array(
            'form' => $paypalModel->getPaypalForm($orderId)
        );

        $answer = ipView('view/page/paymentRedirect.php', $data)->render();


        return $answer;

    }
}
