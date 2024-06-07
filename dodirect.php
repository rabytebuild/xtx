<?php
function login() {
    $login_url = "https://faucetearner.org/api.php?act=login";
    $login_headers = [
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

    $login_data = json_encode([
        "email" => "rhsalisu",
        "password" => "Rabiu2004@"
    ]);

    $ch = curl_init($login_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $login_headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data);
    curl_setopt($ch, CURLOPT_HEADER, true); // Include headers in the output

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    curl_close($ch);

    if ($httpCode == 200) {
        $response_data = json_decode($body, true);
        if ($response_data && isset($response_data["code"]) && $response_data["code"] == 0) {
            echo "Login successful\n";
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $matches);
            $cookies = array();
            foreach($matches[1] as $item) {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }
            return $cookies;
        } else {
            echo "Login failed: " . ($response_data["message"] ?? "Unknown error") . "\n";
            return null;
        }
    } else {
        echo "Failed to login: HTTP $httpCode\n";
        return null;
    }
}

function faucet($cookies) {
    $faucet_url = "https://faucetearner.org/api.php?act=faucet";
    $cookie_string = "googtrans=/en/en";
    foreach ($cookies as $key => $value) {
        $cookie_string .= "; $key=$value";
    }

    $faucet_headers = [
        "Cookie: $cookie_string",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0",
        "Accept: application/json, text/javascript, */*; q=0.01",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: gzip, deflate, br",
        "Content-Type: application/json",
        "X-Requested-With: XMLHttpRequest",
        "Origin: https://faucetearner.org",
        "Dnt: 1",
        "Referer: https://faucetearner.org/faucet.php",
        "Sec-Fetch-Dest: empty",
        "Sec-Fetch-Mode: cors",
        "Sec-Fetch-Site: same-origin",
        "Priority: u=1",
        "Te: trailers"
    ];

    $ch = curl_init($faucet_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $faucet_headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response_data = json_decode($response, true);

    curl_close($ch);

    if ($httpCode == 200 && isset($response_data["code"])) {
        if ($response_data["code"] == 0) {
            preg_match('/<span translate=\'no\' class=\'text-info fs-2\'>(.*?)<\/span>/', $response_data["message"], $matches);
            $amount = $matches[1] ?? 'unknown amount';
            echo "Request successful: Received $amount\n";
            return true;
        } elseif ($response_data["code"] == 2) {
            echo "Wave missed: " . $response_data["message"] . "\n";
            return false;
        }
    } else {
        echo "Failed to request: HTTP $httpCode - " . $response . "\n";
        return false;
    }
}

$cookies = login();
if (!$cookies) {
    echo "Exiting due to failed login.\n";
    exit;
}

while (true) {
    if (!faucet($cookies)) {
        $cookies = login();
        if (!$cookies) {
            echo "Exiting due to failed login.\n";
            break;
        }
    }
    sleep(60);
}
?>
