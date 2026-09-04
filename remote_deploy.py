import os
import sys
import paramiko

HOST = '46.202.186.86'
PORT = 65002
USER = 'u664715641'
PASS = 'Ilhammaulana23'

pub_key_path = os.path.expanduser('~/.ssh/id_ed25519.pub')
pub_key_content = ""
if os.path.exists(pub_key_path):
    with open(pub_key_path, 'r', encoding='utf-8') as f:
        pub_key_content = f.read().strip()

def run():
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        client.connect(
            hostname=HOST, port=PORT, username=USER, password=PASS,
            timeout=10, banner_timeout=30, auth_timeout=10,
            look_for_keys=False, allow_agent=False
        )
        print("Paramiko Password Auth Successful!")
        
        # 1. Authorize SSH public key so native ssh works automatically!
        if pub_key_content:
            cmd_auth = (
                f"mkdir -p ~/.ssh && chmod 700 ~/.ssh && "
                f"grep -qF '{pub_key_content}' ~/.ssh/authorized_keys 2>/dev/null || "
                f"echo '{pub_key_content}' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"
            )
            client.exec_command(cmd_auth)
            print("SSH Public Key Added to Hostinger authorized_keys!")

        # 2. Run deploy
        deploy_cmd = (
            "cd domains/buyle.id/public_html && "
            "git checkout . && "
            "git pull origin main && "
            "php artisan migrate --force && "
            "php artisan favicon:refresh && "
            "php artisan optimize:clear"
        )
        stdin, stdout, stderr = client.exec_command(deploy_cmd)
        out = stdout.read().decode('utf-8', errors='ignore')
        err = stderr.read().decode('utf-8', errors='ignore')
        print("STDOUT:\n" + out)
        if err:
            print("STDERR:\n" + err)
        client.close()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == '__main__':
    run()
