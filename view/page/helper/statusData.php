<table>
    <tr>
        <td><b><?php echo __('Order ID', 'PayPal') ?></b></td>
        <td><?php echo esc($order['id']) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Paid', 'PayPal') ?></b></td>
        <td><?php echo esc(__($order['isPaid'] ? 'Yes' : 'No', 'PayPal')) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Item', 'PayPal') ?></b></td>
        <td><?php echo esc($order['item']) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Amount', 'PayPal') ?></b></td>
        <td><?php echo esc(ipFormatPrice($order['price'], $order['currency'], 'PayPal')) ?></td>
    </tr>
    <tr>
        <td><b><?php echo __('Date', 'PayPal') ?></b></td>
        <td><?php echo esc(ipFormatDateTime(strtotime($order['createdAt']), 'PayPal')) ?></td>
    </tr>
</table>
