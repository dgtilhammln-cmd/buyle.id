import subprocess
import os
import time

cmd = [
    os.path.abspath('plink.exe'),
    '-hostkey', 'SHA256:LeBsjJUGP0sny5IZPPNzyPZ+8B5EgCqYMGYVifqicZE',
    '-P', '65002',
    'u664715641@46.202.186.86',
    'echo SSH_KEY_TEST_SUCCESS'
]

p = subprocess.Popen(cmd, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
stdout, stderr = p.communicate(input="Ilhammaulana23\n", timeout=15)

print("EXIT:", p.returncode)
print("STDOUT:", stdout)
print("STDERR:", stderr)
