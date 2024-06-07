import requests
import time

def login():
    url = "https://faucetearner.org/api.php?act=login"
    headers = {
        "Host": "faucetearner.org",
        "Cookie": "googtrans=/en/en",
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0",
        "Accept": "application/json, text/javascript, */*; q=0.01",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "Origin": "https://faucetearner.org",
        "Dnt": "1",
        "Referer": "https://faucetearner.org/login.php",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Priority": "u=1",
        "Te": "trailers"
    }
    payload = {
        "email": "test",
        "password": "test"
    }

    response = requests.post(url, headers=headers, json=payload)

    if response.status_code == 200:
        print("Login successful:", response.json())
    else:
        print("Failed to login:", response.status_code, response.text)

while True:
    login()
    time.sleep(60)
