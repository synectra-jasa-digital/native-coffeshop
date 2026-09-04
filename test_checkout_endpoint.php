<?php
$url = "http://localhost/native-coffeshop/pos/checkout";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$data = json_encode([
    'order_type' => 'dine_in',
    'table_id' => '1',
    'grand_total' => 15000,
    'subtotal' => 15000,
    'tax_amount' => 0,
    'items' => [
        [
            'id' => 1,
            'variant_id' => null,
            'qty' => 1,
            'price' => 15000,
            'note' => ''
        ]
    ]
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-Requested-With:XMLHttpRequest', 'Accept:application/json'));
$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP $httpcode\n";
echo "RESPONSE: $result\n";
