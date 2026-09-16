import urllib.request
import json

hashid = "Pv23p2E"
user = "mindiw"

# Test endpoints
urls = [
    f"https://lynk.id/v1/product/{hashid}",
    f"https://lynk.id/v1/products/{hashid}",
    f"https://lynk.id/v1/product/detail/{hashid}",
    f"https://lynk.id/v1/product/desc/{hashid}",
    f"https://lynk.id/v1/content/product/{hashid}",
    f"https://lynk.id/v1/store/product/{hashid}",
    f"https://lynk.id/v1/creator/{user}/product/{hashid}",
    f"https://lynk.id/v1/page/product/{hashid}",
    f"https://lynk.id/v1/description?hashid={hashid}",
]

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    'Accept': 'application/json, text/plain, */*',
    'X-Requested-With': 'XMLHttpRequest',
    'Referer': f'https://lynk.id/{user}/{hashid}'
}

for u in urls:
    try:
        req = urllib.request.Request(u, headers=headers)
        res = urllib.request.urlopen(req, timeout=4)
        body = res.read().decode('utf-8')
        print(f"SUCCESS GET {u} -> Status: {res.status}, Len: {len(body)}")
        print(f"Body: {body[:300]}")
    except Exception as e:
        print(f"FAIL GET {u} -> {e}")

# Also test POST
post_data = json.dumps({"hashid": hashid, "username": user}).encode('utf-8')
post_urls = [
    f"https://lynk.id/v1/product/description",
    f"https://lynk.id/v1/product/detail",
    f"https://lynk.id/v1/product/get",
    f"https://lynk.id/v1/content/get",
]

for u in post_urls:
    try:
        req = urllib.request.Request(u, data=post_data, headers={**headers, 'Content-Type': 'application/json'})
        res = urllib.request.urlopen(req, timeout=4)
        body = res.read().decode('utf-8')
        print(f"SUCCESS POST {u} -> Status: {res.status}, Len: {len(body)}")
        print(f"Body: {body[:300]}")
    except Exception as e:
        print(f"FAIL POST {u} -> {e}")
