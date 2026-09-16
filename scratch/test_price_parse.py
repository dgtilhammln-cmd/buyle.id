import re

with open(r'c:\Users\dgtil\Downloads\PENTING\HVM Digital\buyle.id ex alaatrumah\lynk.id source view', 'r', encoding='utf-8') as f:
    rawHtml = f.read()

price = 0
salePrice = 0

if m := re.search(r'var p\s*=\s*_g\([\'"]([\d.]+)[\'"]\)', rawHtml):
    price = float(m.group(1))
if m := re.search(r'var sPrice\s*=\s*_g\([\'"]([\d.]+)[\'"]\)', rawHtml):
    salePrice = float(m.group(1))

if price > 0 and salePrice > 0:
    if salePrice > price:
        price, salePrice = salePrice, price
    elif price == salePrice:
        salePrice = 0

print('HARGA NORMAL:', int(price))
print('HARGA PROMO:', int(salePrice))
