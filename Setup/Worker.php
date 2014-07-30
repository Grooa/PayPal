<?php
/**
 * @package   ImpressPages
 */




namespace Plugin\PayPal\Setup;


class Worker
{
    public function activate()
    {

        $table = ipTable('paypal');
        $sql="
        CREATE TABLE IF NOT EXISTS $table (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `userId` int(11) NOT NULL,
          `item` varchar(255) NOT NULL,
          `currency` varchar(3) NOT NULL,
          `price` int(11) NOT NULL COMMENT 'in cents',
          `isPaid` tinyint(1) DEFAULT 0,
          `createdAt` datetime NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

        ";

        ipDb()->execute($sql);
    }
}
