import requests
import time

def faucet():
    url = "https://faucetearner.org/api.php?act=faucet"
    headers = {
        "Host": "faucetearner.org",
        "Cookie": "googtrans=/en/en; login=1; user=540501902014-102.91.92.209; show_nt1=1; Hm_lvt_2b147ccaeef7e49f5f9553cadfdf8428=1717713048; Hm_lpvt_2b147ccaeef7e49f5f9553cadfdf8428=1717713048",
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0",
        "Accept": "application/json, text/javascript, */*; q=0.01",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "Origin": "https://faucetearner.org",
        "Dnt": "1",
        "Referer": "https://faucetearner.org/faucet.php",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Priority": "u=1",
        "Te": "trailers"
    }
    payload = {}

    response = requests.post(url, headers=headers, json=payload)

    if response.status_code == 200:
        print("Request successful:", response.json())
    else:
        print("Failed to request:", response.status_code, response.text)

while True:
    faucet()
    time.sleep(60)
