<?php
function login() {
    $url = "https://faucetearner.org/api.php?act=login";
    $headers = [
        "Cookie: googtrans=/en/en",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0",
        "Accept: application/json, text/javascript, */*; q=0.01",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: gzip, deflate, br",
        "Content-Type: application/json",
        "X-Requested-With: XMLHttpRequest",
        "Origin: https://faucetearner.org",
        "Dnt: 1",
        "Referer: https://faucetearner.org/login.php",
        "Sec-Fetch-Dest: empty",
        "Sec-Fetch-Mode: cors",
        "Sec-Fetch-Site: same-origin",
        "Priority: u=1",
        "Te: trailers"
    ];

    $data = json_encode([
        "email" => "test",
        "password" => "test"
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200) {
        echo "Login successful: " . $response . "\n";
    } else {
        echo "Failed to login: HTTP " . $httpCode . " - " . $response . "\n";
    }

    curl_close($ch);
}

while (true) {
    login();
    sleep(60);
}
?>
