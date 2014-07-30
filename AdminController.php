<?php
/**
 * @package   ImpressPages
 */


/**
 * Created by PhpStorm.
 * User: mangirdas
 * Date: 7/30/14
 * Time: 2:19 PM
 */

namespace Plugin\PayPal;


class AdminController {
    public function index()
    {
        $config = array(
            'table' => 'paypal',
            'fields' => array(
                array(
                    'label' => __('Id', 'PayPal', false),
                    'field' => 'id',
                    'allowUpdate' => false,
                    'allowInsert' => false
                ),
                array(
                    'label' => __('User ID', 'PayPal', false),
                    'field' => 'userId',
                    'type' => 'Integer'
                ),
                array(
                    'label' => __('Item', 'PayPal', false),
                    'field' => 'item'
                ),
                array(
                    'label' => __('Currency', 'PayPal', false),
                    'field' => 'currency'
                ),
                array(
                    'label' => __('Price', 'PayPal', false),
                    'field' => 'price',
                    'type' => 'Currency'
                ),
                array(
                    'label' => __('Paid', 'PayPal', false),
                    'field' => 'isPaid',
                    'type' => 'Checkbox'
                ),
                array(
                    'label' => __('Created At', 'PayPal', false),
                    'field' => 'createdAt'
                ),



            )
        );
        return ipGridController($config);
    }
}
