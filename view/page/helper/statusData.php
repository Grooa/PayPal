<table>
    <tr>
        <td><b><?php echo __('Order ID', 'PayPal') ?></b></td>
        <td><?php echo esc($payment['orderId']) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Paid', 'PayPal') ?></b></td>
        <td><?php echo __($payment['isPaid'] ? 'Yes' : 'No', 'PayPal') ?>
            <?php if (!$payment['isPaid']) { ?>
                <a href="<?php echo $paymentUrl ?>">(<?php echo __('Pay Now', 'PayPal') ?>)</a>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td><b><?php echo __('Item', 'PayPal') ?></b></td>
        <td><?php echo esc($payment['title']) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Amount', 'PayPal') ?></b></td>
        <td><?php echo esc(ipFormatPrice($payment['price'], $payment['currency'], 'PayPal')) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Date', 'PayPal') ?></b></td>
        <td><?php echo esc(ipFormatDateTime(strtotime($payment['createdAt']), 'PayPal')) ?></td>
    </tr>
</table>
