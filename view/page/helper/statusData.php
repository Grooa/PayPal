<b><?php echo __('Order ID', 'PayPal') ?>:</b> <?php echo esc($order['id']) ?><br/>
<b><?php echo __('Paid', 'PayPal') ?>:</b> <?php echo esc(__($order['isPaid'] ? 'Yes' : 'No', 'PayPal')) ?><br/>
<b><?php echo __('Item', 'PayPal') ?>:</b> <?php echo esc($order['item']) ?><br/>
<b><?php echo __('Price', 'PayPal') ?>:</b> <?php echo esc(ipFormatPrice($order['price'], $order['currency'], 'PayPal')) ?><br/>
<b><?php echo __('Date', 'PayPal') ?>:</b> <?php echo esc(ipFormatDateTime(strtotime($order['createdAt']), 'PayPal')) ?><br/>
