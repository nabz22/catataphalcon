#!/usr/bin/env python3
"""
Deployment script untuk catataphalcon project
Deploy ke: 192.168.0.73:/home/fdx/dockerizer/
"""

import subprocess
import sys
import paramiko
from paramiko import SSHClient, AutoAddPolicy
import time

# Configuration
SSH_HOST = "192.168.0.73"
SSH_USER = "fdx"
SSH_PASSWORD = "k2Zd2qS2j"
SSH_PORT = 22
DEPLOY_BASE = "/home/fdx/dockerizer"

def print_section(title):
    """Print a formatted section title"""
    print("\n" + "=" * 42)
    print(f"🚀 {title}")
    print("=" * 42 + "\n")

def execute_remote_command(ssh_client, command):
    """Execute command on remote server via SSH"""
    try:
        stdin, stdout, stderr = ssh_client.exec_command(command, get_pty=True)
        
        # Print output in real-time
        for line in stdout:
            print(line.rstrip())
        
        # Check for errors
        error_output = stderr.read().decode()
        if error_output:
            print(f"⚠️  Warning: {error_output}")
        
        return stdout.channel.recv_exit_status()
    except Exception as e:
        print(f"❌ Error executing command: {e}")
        return -1

def main():
    print("=" * 42)
    print("🚀 DEPLOYMENT SCRIPT")
    print("=" * 42)
    print(f"Target: {SSH_USER}@{SSH_HOST}")
    print(f"Deploy Dir: {DEPLOY_BASE}")
    
    # Connect to SSH
    try:
        ssh = SSHClient()
        ssh.set_missing_host_key_policy(AutoAddPolicy())
        
        print("\n📡 Connecting to server...")
        ssh.connect(
            SSH_HOST,
            port=SSH_PORT,
            username=SSH_USER,
            password=SSH_PASSWORD,
            timeout=10,
            look_for_keys=False,
            allow_agent=False
        )
        print("✅ Connected!")
        
    except Exception as e:
        print(f"❌ Failed to connect: {e}")
        return 1
    
    try:
        # 1. Create directory structure
        print_section("Creating Directory Structure")
        execute_remote_command(ssh, f"mkdir -p {DEPLOY_BASE} && ls -la {DEPLOY_BASE}")
        
        # 2. Clone/update catataphalcon
        print_section("Cloning/Updating catataphalcon")
        cmd = f"""cd {DEPLOY_BASE} && \
if [ -d 'catataphalcon' ]; then \
    echo '📥 Updating existing repository...'; \
    cd catataphalcon && \
    git pull origin main 2>/dev/null || git pull origin master; \
else \
    echo '📥 Cloning new repository...'; \
    git clone https://github.com/nabz22/catataphalcon.git catataphalcon; \
fi && \
echo '✅ Repository ready' && \
ls -la catataphalcon | head -15"""
        execute_remote_command(ssh, cmd)
        
        # 3. Check Docker
        print_section("Checking Docker Installation")
        execute_remote_command(ssh, "docker --version && docker-compose --version")
        
        # 4. Build Docker images
        print_section("Building Docker Images")
        execute_remote_command(ssh, f"cd {DEPLOY_BASE}/catataphalcon && docker-compose build --no-cache")
        
        # 5. Start containers
        print_section("Starting Containers")
        execute_remote_command(ssh, f"cd {DEPLOY_BASE}/catataphalcon && docker-compose up -d")
        
        # 6. Show container status
        print_section("Container Status")
        execute_remote_command(ssh, f"cd {DEPLOY_BASE}/catataphalcon && docker-compose ps")
        
        # 7. Show summary
        print_section("✅ DEPLOYMENT COMPLETE!")
        print(f"📂 Base Directory: {DEPLOY_BASE}")
        print()
        print("🔗 Access Your Application:")
        print("  - App: http://192.168.0.73:8080")
        print("  - DB Admin: http://192.168.0.73:8090 (PhpMyAdmin)")
        print()
        print("📋 Database Credentials:")
        print("  - Host: db (from inside container)")
        print("  - User: root")
        print("  - Password: root")
        print()
        print("=" * 42)
        
    except Exception as e:
        print(f"❌ Deployment failed: {e}")
        return 1
    finally:
        ssh.close()
    
    return 0

if __name__ == "__main__":
    sys.exit(main())
