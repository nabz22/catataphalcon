#!/bin/bash

# Deployment script untuk server di 192.168.0.73
# Dijalankan dari server lokal

set -e

# SSH Configuration
SSH_HOST="192.168.0.73"
SSH_USER="fdx"
SSH_PASSWORD="k2Zd2qS2j"
DEPLOY_BASE="/home/fdx/dockerizer"

echo "=========================================="
echo "🚀 Starting Remote Deployment"
echo "=========================================="
echo "Target Server: $SSH_USER@$SSH_HOST:$DEPLOY_BASE"
echo ""

# Function to run remote command
run_remote() {
    local cmd="$1"
    sshpass -p "$SSH_PASSWORD" ssh -o StrictHostKeyChecking=no -o ConnectTimeout=5 \
        "$SSH_USER@$SSH_HOST" "$cmd"
}

# 1. Create directory structure on server
echo "📁 Creating directory structure on server..."
run_remote "mkdir -p $DEPLOY_BASE && ls -la $DEPLOY_BASE"

echo ""
echo "=========================================="
echo "📥 Cloning/Updating Repositories"
echo "=========================================="
echo ""

# 2. Deploy catataphalcon
echo "📦 Deploying catataphalcon..."
run_remote "cd $DEPLOY_BASE && \
    if [ -d 'catataphalcon' ]; then \
        cd catataphalcon && git pull origin main 2>/dev/null || git pull origin master; \
    else \
        git clone https://github.com/nabz22/catataphalcon.git catataphalcon; \
    fi && \
    ls -la catataphalcon | head -10"

echo "✅ catataphalcon ready"
echo ""

# 3. Deploy notes-app (assuming it's in the same repo)
echo "📦 Deploying notes-app..."
run_remote "cd $DEPLOY_BASE/catataphalcon && ls -la notes-app"

echo "✅ notes-app ready"
echo ""

echo "=========================================="
echo "🐳 Checking Docker on Server"
echo "=========================================="
echo ""

run_remote "docker --version && docker-compose --version"

echo ""
echo "=========================================="
echo "🔨 Building and Starting Containers"
echo "=========================================="
echo ""

# 4. Build dan start catataphalcon
echo "🐳 Building catataphalcon containers..."
run_remote "cd $DEPLOY_BASE/catataphalcon && docker-compose build --no-cache"

echo ""
echo "🚀 Starting catataphalcon containers..."
run_remote "cd $DEPLOY_BASE/catataphalcon && docker-compose up -d && docker-compose ps"

echo ""
echo "✅ Deployment Complete!"
echo ""
echo "=========================================="
echo "📝 DEPLOYMENT SUMMARY"
echo "=========================================="
echo ""
echo "📂 Deployment Base: $DEPLOY_BASE"
echo ""
echo "🔗 Access Your Applications:"
echo "  - CatataPhalcon App: http://192.168.0.73:8080"
echo "  - CatataPhalcon DB:  http://192.168.0.73:8090 (PhpMyAdmin)"
echo ""
echo "📋 SSH Into Server:"
echo "  ssh fdx@192.168.0.73"
echo ""
echo "📋 Useful Remote Commands:"
echo "  View logs:"
echo "    docker-compose -f $DEPLOY_BASE/catataphalcon/docker-compose.yml logs -f app"
echo ""
echo "  Restart:"
echo "    docker-compose -f $DEPLOY_BASE/catataphalcon/docker-compose.yml restart"
echo ""
echo "  Stop:"
echo "    docker-compose -f $DEPLOY_BASE/catataphalcon/docker-compose.yml stop"
echo ""
echo "=========================================="
