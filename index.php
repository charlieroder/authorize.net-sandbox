<!-- 
    basic request to Authorize.net sandbox endpoint 
-->

<?php

$xml = file_get_contents('credit-card-request.xml');

$url = 'https://apitest.authorize.net/xml/v1/request.api';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

curl_setopt($curl, CURLOPT_POST, true);

curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl);

if(curl_errno($curl)){
    throw new Exception(curl_error($curl));
}

curl_close($curl);

echo $result;

?>