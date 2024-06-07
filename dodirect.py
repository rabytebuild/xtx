import requests
import time

LOGIN_URL = "https://faucetearner.org/api.php?act=login"
FAUCET_URL = "https://faucetearner.org/api.php?act=faucet"
HEADERS = {
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

def login():
    login_data = {
        "email": "rhsalisu",
        "password": "Rabiu2004@"
    }

    response = requests.post(LOGIN_URL, json=login_data, headers=HEADERS)
    if response.status_code == 200:
        response_data = response.json()
        if response_data.get("code") == 0:
            print("Login successful")
            return response.cookies
        else:
            print(f"Login failed: {response_data.get('message', 'Unknown error')}")
            return None
    else:
        print(f"Failed to login: HTTP {response.status_code}")
        return None

def faucet(cookies):
    faucet_headers = HEADERS.copy()
    faucet_headers["Referer"] = "https://faucetearner.org/faucet.php"
    
    response = requests.post(FAUCET_URL, json={}, headers=faucet_headers, cookies=cookies)
    if response.status_code == 200:
        response_data = response.json()
        if response_data.get("code") == 0:
            message = response_data.get("message", "")
            amount = "unknown amount"
            if "text-info fs-2" in message:
                amount = message.split('text-info fs-2">')[1].split("<")[0]
            print(f"Request successful: Received {amount}")
            return True
        elif response_data.get("code") == 2:
            print(f"Wave missed: {response_data.get('message')}")
            return False
    else:
        print(f"Failed to request: HTTP {response.status_code} - {response.text}")
        return False

def main():
    cookies = login()
    if not cookies:
        print("Exiting due to failed login.")
        return

    while True:
        if not faucet(cookies):
            cookies = login()
            if not cookies:
                print("Exiting due to failed login.")
                break
        time.sleep(60)

if __name__ == "__main__":
    main()
