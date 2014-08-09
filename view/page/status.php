<?php echo ipRenderWidget('Heading', array('title' => __('Payment status', 'PayPal'))) ?>
<?php echo ipRenderWidget('Text', array('text' => ipView('helper/statusData.php', $this->getVariables()))) ?>
