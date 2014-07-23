<?php
/**
 * @package   ImpressPages
 */


namespace Plugin\PayPal;


class Event
{
    public static function ipBeforeController()
    {
        ipAddJs('assets/PayPal.js');
    }
}
