<?php

$payment = $mollie->payments->get($payment->id);

if ($payment->isPaid())
{
    echo "Payment received.";
}

?>
