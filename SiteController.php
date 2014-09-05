<?php
/**
 * @package   ImpressPages
 */



namespace Plugin\PayPal;


class SiteController extends \Ip\Controller
{
    public function pay($paymentId, $securityCode)
    {
        $payment = Model::getPayment($paymentId);
        if (!$payment) {
            throw new \Ip\Exception('Payment ' . $paymentId . ' doesn\'t exist');
        }

        if ($payment['securityCode'] != $securityCode) {
            throw new \Ip\Exception('Payment security code is incorrect. Order Id '. $paymentId . '. SecurityCode ' . $securityCode);
        }



        if (!$payment['userId'] && ipUser()->loggedIn()) {
            Model::update($paymentId, array('userId' => ipUser()->userId()));
        }

        $paymentModel = PayPalModel::instance();

        if (!$payment['isPaid'] && $paymentModel->isSkipMode()) {
            $paymentModel->markAsPaid($paymentId);
            $payment = Model::getPayment($paymentId);
        }


        if ($payment['isPaid']) {
            $response = Helper::responseAfterPayment($paymentId, $securityCode);
            $answer = $response;
        } else {
            //redirect to the payment
            $paypalModel = PayPalModel::instance();

            try {
                $data = array(
                    'form' => $paypalModel->getPaypalForm($paymentId)
                );
            } catch (\Ip\Exception $e) {
                return $e->getMessage();
            }

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
