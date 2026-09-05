import os

themes = ["theme1","theme2","theme3","theme4"]
base = r"resources\views\bio"

for t in themes:
    path = os.path.join(base, t + ".blade.php")
    with open(path, "r", encoding="utf-8") as f:
        lines = f.readlines()
    
    new_lines = []
    i = 0
    while i < len(lines):
        line = lines[i]
        new_lines.append(line)
        
        # Deteksi baris @include bio._social_icons
        if "_social_icons" in line and "@include" in line:
            i += 1
            # Skip sisa blok lama hingga ketemu baris search-box atau {{-- Realtime
            while i < len(lines):
                l = lines[i]
                s = l.strip()
                if ("fa-whatsapp" in l or "fa-instagram" in l or 
                    "fa-tiktok" in l or "fa-youtube" in l or
                    ("config" in l and ("wa" in l or "ig" in l) and "href" in l)):
                    i += 1
                    continue
                if s == "</div>" or s == "@endif" or s == "":
                    i += 1
                    continue
                # Baris bukan sisa lama, stop skip
                break
        else:
            i += 1
    
    with open(path, "w", encoding="utf-8") as f:
        f.writelines(new_lines)
    
    print("Done:", t, "- new lines:", len(new_lines))
