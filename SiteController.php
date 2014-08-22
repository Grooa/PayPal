<?php
/**
 * @package   ImpressPages
 */



namespace Plugin\PayPal;


class SiteController extends \Ip\Controller
{
    public function pay($paymentId, $securityCode)
    {
        $order = Model::getPayment($paymentId);
        if (!$order) {
            throw new \Ip\Exception('Order ' . $paymentId . ' doesn\'t exist');
        }



        if (!$order['userId'] && ipUser()->loggedIn()) {
            Model::update($paymentId, array('userId' => ipUser()->userId()));
        }

        $paymentModel = PayPalModel::instance();
        if (!$order['isPaid'] && $paymentModel->isSkipMode()) {
            $paymentModel->markAsPaid($paymentId);
            $order = Model::getPayment($paymentId);
        }


        if ($order['isPaid']) {
            $response = Helper::responseAfterPayment($paymentId, $securityCode);
            $answer = $response;
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
        $payment = Model::getPayment($paymentId);
        if (!$payment) {
            throw new \Ip\Exception('Unknown order. Id: ' . $paymentId);
        }
        if ($payment['securityCode'] != $securityCode) {
            throw new \Ip\Exception('Incorrect order security code');
        }

        $data = array(
            'payment' => $payment,
            'paymentUrl' => ipRouteUrl('PayPal_pay', array('paymentId' => $payment['id'], 'securityCode' => $payment['securityCode']))
        );
        $view = ipView('view/page/status.php', $data);
        return $view;
    }
}
