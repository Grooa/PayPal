<?php
/**
 * @package   ImpressPages
 */


namespace Plugin\PayPal;


class PayPalModel
{
    const PAYPAL_POST_URL_TEST = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    const PAYPAL_POST_URL = 'https://www.paypal.com/cgi-bin/webscr';

    const MODE_PRODUCTION = 'Production';
    const MODE_TEST = 'Test';
    const MODE_SKIP = 'Skip';


    protected static $instance;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * Get singleton instance
     * @return PayPalModel
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new PayPalModel();
        }

        return self::$instance;
    }

    public function processPayPalCallback($postData)
    {
        if (empty($postData['txn_type'])) {
            return;
        }

        $postUrl = $this->getPayPalUrl();

        $response = $this->httpPost($postUrl, $postData);

        if (!$response["status"]) {
            ipLog()->error(
                'PayPal.ipn: notification check error',
                $response
            );
            return;
        }

        $customData = json_decode($postData['custom'], true);

        $orderId = isset($customData['orderId']) ? $customData['orderId'] : null;
        $currency = isset($postData['mc_currency']) ? $postData['mc_currency'] : null;
        $receiver = isset($postData['receiver_email']) ? $postData['receiver_email'] : null;
        $amount = isset($postData['amount']) ? $postData['amount'] : null;
        $test = isset($postData['test_ipn']) ? $postData['test_ipn'] : null;


        if ($test != $this->isTestMode()) {
            ipLog()->error('PayPal.ipn: IPN rejected. Test mode conflict', $response);
            return;
        }





        $order = Model::getOrder($orderId);

        if (!$order) {
            ipLog()->error('PayPal.ipn: Order not found.', array('orderId' => $orderId));
            return;
        }

        if ($order['currency'] != $currency) {
            ipLog()->error('PayPal.ipn: IPN rejected. Currency doesn\'t match', array('paypal currency' => $currency, 'expected currency' => $order['currency']));
            return;
        }

        $orderPrice = substr_replace($order['price'], '.', -2, 0);
        if ($amount != $orderPrice) {
            ipLog()->error('PayPal.ipn: IPN rejected. Price doesn\'t match', array('paypal price' => $amount, 'expected price' => '' . $orderPrice));
            return;
        }

        if ($receiver != $this->getEmail()) {
            ipLog()->error('PayPal.ipn: IPN rejected. Recipient doesn\'t match', array('paypal recipient' => $receiver, 'expected recipient' => $this->getEmail()));
            return;
        }

        if ($response["httpResponse"] != 'VERIFIED') {
            ipLog()->error('PayPal.ipn: Paypal doesn\'t recognize the payment', $response);
            return;
        }

        if ($order['isPaid']) {
            ipLog()->error('PayPal.ipn: Order is already paid', $response);
            return;
        }

        $info = array(
            'orderId' => $order['id'],
            'item' => $order['item'],
            'userId' => $order['userId']
        );

        ipLog()->info('PayPal.ipn: Successful payment', $info);

        ipEvent('ipPaymentReceived', $info);
        Model::update($orderId, array('isPaid' => 1));




    }


    /**
     *
     * Enter description here ...
     * @param string $url
     * @param array $values
     * @return array
     */
    private function httpPost($url, $values)
    {
        $tmpAr = array_merge($values, array("cmd" => "_notify-validate"));
        $postFieldsAr = array();
        foreach ($tmpAr as $name => $value) {
            $postFieldsAr[] = "$name=" . urlencode($value);
        }
        $postFields_ = implode("&", $postFieldsAr);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields_);

        //getting response from server
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            return array("status" => false, "error_msg" => curl_error($ch), "error_no" => curl_errno($ch));
        }


        return array("status" => true, "httpResponse" => $httpResponse);

    }

    public function getPaypalForm($orderId)
    {
        if (!$this->getEmail()) {
            throw new \Ip\Exception('Please enter configuration values for PayPal plugin');
        }


        $order = Model::getOrder($orderId);
        if (!$order) {
            throw new \Ip\Exception("Can't find order id. " . $orderId);
        }


        $currency = $order['currency'];
        $privateData = array(
            'orderId' => $orderId,
            'userId' => $order['userId']
        );



        $values = array(
            'cmd' => '_xclick',
            'character' => 'utf-8',
            'business' => $this->getEmail(),
            'amount' => $order['price'] / 100,
            'currency_code' => $currency,
            'no_shipping' => 1,
            'custom' => json_encode($privateData),
            'return' => ipRouteUrl('PayPal_ipn'),
            'notify_url' => ipRouteUrl('PayPal_ipn'),
            'item_name' => $order['item'],
            'item_number' => $order['id']
        );

        $form = new \Ip\Form();
        $form->addClass('ipsPayPalAutosubmit');
        $form->setAction($this->getPayPalUrl());
        $form->setAjaxSubmit(0);

        foreach ($values as $valueKey => $value) {
            $field = new \Ip\Form\Field\Hidden(
                array(
                    'name' => $valueKey,
                    'value' => $value
                ));
            $form->addField($field);
        }

        $form->setMethod(\Ip\Form::METHOD_POST);
        return $form;
    }

    /**
     *
     *  Returns $data encoded in UTF8. Very useful before json_encode as it fails if some strings are not utf8 encoded
     * @param mixed $dat array or string
     * @return array
     */
    private function checkEncoding($dat)
    {
        if (is_string($dat)) {
            if (mb_check_encoding($dat, 'UTF-8')) {
                return $dat;
            } else {
                return utf8_encode($dat);
            }
        }
        if (is_array($dat)) {
            $answer = array();
            foreach ($dat as $i => $d) {
                $answer[$i] = $this->checkEncoding($d);
            }
            return $answer;
        }
        return $dat;
    }


    public function getEmail()
    {
        if ($this->isTestMode()) {
            return ipGetOption('PayPal.paypalEmailTest');
        } else {
            return ipGetOption('PayPal.paypalEmail');
        }
    }

    public function getPayPalUrl()
    {
        if ($this->isTestMode()) {
            return self::PAYPAL_POST_URL_TEST;
        } else {
            return self::PAYPAL_POST_URL;
        }
    }

    public function isTestMode()
    {
        return ipGetOption('PayPal.mode') == self::MODE_TEST;
    }


    public function isSkipMode()
    {
        return ipGetOption('PayPal.mode') == self::MODE_SKIP;
    }

    public function isProductionMode()
    {
        return ipGetOption('PayPal.mode') == self::MODE_PRODUCTION;
    }

    public function correctConfiguration()
    {
        if ($this->getActive() && $this->getEmail()) {
            return true;
        } else {
            return false;
        }
    }

}
