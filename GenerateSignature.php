
    <?php
    $params = array(
        'AWSAccessKeyId' => AWS_ACCESS_KEY_ID,
        'Action' => "ListCustomers",
        'SellerId' => MERCHANT_ID,
        'SignatureMethod' => "HmacSHA256",
        'SignatureVersion' => "2",
        'Timestamp'=> gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()),
        'Version'=> "2014-03-01");

    // Sort the URL parameters
    $url_parts = array();
    foreach(array_keys($params) as $key)
        $url_parts[] = $key . "=" . str_replace('%7E', '~', rawurlencode($params[$key]));

    sort($url_parts);

    // Construct the string to sign
    $url_string = implode("&", $url_parts);
    $string_to_sign = "GET\nmws.amazonservices.com\n/CustomerInformation/2014-03-01/\n" . $url_string;

    // Sign the request
    $signature = hash_hmac("sha256", $string_to_sign, AWS_SECRET_ACCESS_KEY, TRUE);

    // Base64 encode the signature and make it URL safe
    $signature = urlencode(base64_encode($signature));
$signature = str_replace("%7E", "~", $signature);

    $url = "https://mws.amazonservices.com/CustomerInformation/2014-03-01/" . '?' . $url_string . "&Signature=" . $signature;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);

    $parsed_xml = simplexml_load_string($response);
	file_put_contents('data.xml',$response);
?>
