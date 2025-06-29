<?php

class PaymentController
{
    public function payment()
    {
        $title = "Payment";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-payment/payment.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}
