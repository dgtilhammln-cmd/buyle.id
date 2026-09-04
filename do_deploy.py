import paramiko
import sys

HOST = '46.202.186.86'
PORT = 65002
USER = 'u664715641'
PASS = 'Ilhammaulana23'

DEPLOY_CMD = (
    "cd domains/buyle.id/public_html "
    "&& git checkout . "
    "&& git pull origin main "
    "&& php artisan optimize:clear"
)

def deploy():
    transport = paramiko.Transport((HOST, PORT))
    try:
        print(f"Connecting to {HOST}:{PORT} ...")
        transport.connect()
        
        # Try password auth
        try:
            transport.auth_password(USER, PASS)
            print("Password auth OK")
        except paramiko.AuthenticationException:
            print("Password auth failed, trying keyboard-interactive...")
            def handler(title, instructions, prompts):
                responses = []
                for prompt, echo in prompts:
                    responses.append(PASS)
                return responses
            transport.auth_interactive(USER, handler)
            print("Keyboard-interactive auth OK")
        
        client = paramiko.SSHClient()
        client._transport = transport
        
        print("Running deploy...")
        stdin, stdout, stderr = client.exec_command(DEPLOY_CMD, timeout=120)
        for line in iter(stdout.readline, ''):
            print(line, end='', flush=True)
        err = stderr.read().decode('utf-8', errors='ignore')
        if err:
            print("STDERR:\n" + err, file=sys.stderr)
        exit_code = stdout.channel.recv_exit_status()
        print(f"\nDone. Exit code: {exit_code}")
    except Exception as e:
        print(f"Error: {type(e).__name__}: {e}", file=sys.stderr)
        sys.exit(1)
    finally:
        transport.close()

if __name__ == '__main__':
    deploy()
