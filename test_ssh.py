import subprocess
import os

passwords = [
    'Ilhammaulana23',
    'ilhammaulana23',
    'Ilhammaulana23!',
]

for pwd in passwords:
    cmd = [
        os.path.abspath('plink.exe'),
        '-batch',
        '-hostkey', 'SHA256:LeBsjJUGP0sny5IZPPNzyPZ+8B5EgCqYMGYVifqicZE',
        '-P', '65002',
        '-pw', pwd,
        'u664715641@46.202.186.86',
        'echo SUCCESS'
    ]
    res = subprocess.run(cmd, capture_output=True, text=True)
    print(f"Testing '{pwd}': exit={res.returncode}")
    print("STDOUT:", res.stdout.strip())
    print("STDERR:", res.stderr.strip())
    if "SUCCESS" in res.stdout:
        print(f"MATCH FOUND: {pwd}")
        break
