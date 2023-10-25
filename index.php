<?php

namespace Main;

include 'classes/Curl.php';

use App\Classes\Curl;

# it`s like data from form
$data = [
    "action" => "SALE",
    "client_key" => "5b6492f0-f8f5-11ea-976a-0242c0a85007",
    "order_id" => "ORDER12340",
    "order_amount" => "1.99",
    "order_currency" => "USD",
    "order_description" => "Product",
    "card_number" => "4111111111111111",
    "card_exp_month" => "01",
    "card_exp_year" => "2025",
    "card_cvv2" => "000",
    "payer_first_name" => "John",
    "payer_last_name" => "Doe",
    "payer_address" => "BigStreet",
    "payer_country" => "US",
    "payer_state" => "CA",
    "payer_city" => "City",
    "payer_zip" => "123456",
    "payer_email" => "doe@example.com",
    "payer_phone" => "199999999",
    "payer_ip" => "123.123.123.123",
    "term_url_3ds" => "http://client.site.com/return.php"
];

#$url = 'https://test.apiurl.com1';
$url = "https://dev-api.rafinita.com/post";
$client_pass = "d0ec0beca8a3c30652746925d5380cf3";

$hash = setHash($data['card_number'], $data['payer_email'], $client_pass);

$curl = new Curl($data, $hash);
$output = $curl->requestCurl($url);
$resultMessage = messageHandler($output);
function setHash(string $card_number, string $email, string $client_pass): string
{
    $hash = md5(strtoupper(strrev($email) . $client_pass .
    strrev(substr($card_number, 0, 6) . substr($card_number, -4))));
    return $hash;
}
function messageHandler(string $output): string
{
    preg_match('#\{(.+)\}#s', $output, $matches);
    $result = json_decode($matches[0]);
    $class = "green";
    if ($result->result != "SUCCESS") {
        $class = "red";
    }
    $message = "<div class='table " . $class . "'>";
    foreach ($result as $key => $value) {
        if (is_array($value)) {
            foreach ($value[0] as $keys => $item) {
                $message .= "<div class='table1'> " . $keys . "  " . $item . "</div>";
            }
        } else {
            if ($key == "error_message") {
            }
            $message .= "<div class='table1'> " . $key . "  " . $value . "</div>";
        }
    }
    $message .= "</div>";
    return $message;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- head definitions go here -->
    </head>
    <body>
        <?php echo $resultMessage ?>
    </body>
</html>

<style>
.table{
  background-color: #f7f7f7;
  padding: 16px;
}
.red{
  border: 1px solid #F74C32;
}
.green{
  border: 1px solid #42df2b;
}
</style>
      