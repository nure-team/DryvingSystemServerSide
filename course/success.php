<?php
global $mysqli;
require_once "blocks/header.php";
require_once(PAYMENT_DIR. 'autoload.php');
require_once "checkout.php";

$user_id = $_GET['uid'];
echo $user_id;

if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) {
    $transaction = $gateway -> completePurchase(array(
       'payer_id'=> $_GET['PayerID'],
       'transactionReference' => $_GET['paymentId'],
    ));

    $response = $transaction->send();

    if ($response->isSuccessful()) {
        // The customer successfully payed
        $arr_body = $response->getData();

        $payment_id = $arr_body['id'];
        $payer_id = $arr_body['payer']['payer_info']['payer_id'];
        $payer_email = $arr_body['payer']['payer_info']['email'];
        $amount = $arr_body['transactions'][0]['amount']['total'];
        $currency = PAYPAL_CURRENCY;
        $payment_status = $arr_body['state'];

        $mysqli->query("INSERT INTO payments(payer_id, payer_email, amount, currency, status, user_id)
        VALUES('" . $payment_id . "', '" . $payer_id . "', '" . $payer_email . "', '" . $amount . "', '" . $currency . "', '" . $payment_status . "', '" . $user_id . "') ");

        echo "<h2 style='color: #52de52'>Payment is successful. Your transaction id is: " .$payment_id. "</h2>";
        echo "<script>location.replace('/?page=course')</script>";

    } else {
        echo $response->getMessage();
    }

} else {
    echo 'Transaction is declined';
}
