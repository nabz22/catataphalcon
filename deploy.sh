#!/bin/bash
# Master Deployment Script untuk Server 192.168.0.73
# Deploy ke: /home/fdx/dockerizer/ dengan struktur subfolder

SERVER="192.168.0.73"
USER="fdx"
DEPLOY_BASE="/home/fdx/dockerizer"

echo "=========================================="
echo "🚀 STARTING DEPLOYMENT TO REMOTE SERVER"
echo "=========================================="
echo "Target: $USER@$SERVER"
echo "Deploy Path: $DEPLOY_BASE"
echo ""

# 1. Create base directory
echo "📁 Creating base directory..."
ssh -o StrictHostKeyChecking=no $USER@$SERVER "mkdir -p $DEPLOY_BASE && echo '✅ Directory created'"

echo ""
echo "=========================================="
echo "📥 CLONING/UPDATING REPOSITORIES"
echo "=========================================="
echo ""

# 2. Clone or update catataphalcon
echo "📦 Deploying catataphalcon..."
ssh -o StrictHostKeyChecking=no $USER@$SERVER << 'SSHEOF'
DEPLOY_BASE="/home/fdx/dockerizer"
cd $DEPLOY_BASE

if [ -d "catataphalcon" ]; then
    echo "  📥 Updating existing repository..."
    cd catataphalcon
    git pull origin main 2>/dev/null || git pull origin master
else
    echo "  📥 Cloning new repository..."
    git clone https://github.com/nabz22/catataphalcon.git catataphalcon
fi

echo "  ✅ Repository ready"
echo ""
echo "  📂 Repository contents:"
ls -la catataphalcon | head -20
SSHEOF

echo ""
echo "=========================================="
echo "🐳 CHECKING DOCKER"
echo "=========================================="
echo ""

ssh -o StrictHostKeyChecking=no $USER@$SERVER << 'SSHEOF'
echo "Docker version:"
docker --version
echo ""
echo "Docker Compose version:"
docker-compose --version || echo "Note: Use 'docker compose' on newer versions"
SSHEOF

echo ""
echo "=========================================="
echo "🔨 BUILDING DOCKER IMAGES"
echo "=========================================="
echo ""

ssh -o StrictHostKeyChecking=no $USER@$SERVER << 'SSHEOF'
cd /home/fdx/dockerizer/catataphalcon
echo "Building images for catataphalcon..."
docker-compose build --no-cache
SSHEOF

echo ""
echo "=========================================="
echo "🚀 STARTING CONTAINERS"
echo "=========================================="
echo ""

ssh -o StrictHostKeyChecking=no $USER@$SERVER << 'SSHEOF'
cd /home/fdx/dockerizer/catataphalcon
echo "Starting containers..."
docker-compose up -d
echo ""
echo "Container status:"
docker-compose ps
SSHEOF

echo ""
echo "=========================================="
echo "✅ DEPLOYMENT COMPLETE!"
echo "=========================================="
echo ""
echo "📝 SUMMARY"
echo "=========================================="
echo ""
echo "📂 Deployment Location: /home/fdx/dockerizer/catataphalcon"
echo ""
echo "🔗 Access Applications:"
echo "  • App: http://192.168.0.73:8080"
echo "  • PhpMyAdmin: http://192.168.0.73:8090"
echo ""
echo "💾 Database:"
echo "  • Host: db (internal container name)"
echo "  • User: root"
echo "  • Password: root"
echo ""
echo "📋 Useful Commands:"
echo ""
echo "  View logs:"
echo "  ssh -o StrictHostKeyChecking=no $USER@$SERVER 'cd /home/fdx/dockerizer/catataphalcon && docker-compose logs -f app'"
echo ""
echo "  Restart containers:"
echo "  ssh -o StrictHostKeyChecking=no $USER@$SERVER 'cd /home/fdx/dockerizer/catataphalcon && docker-compose restart'"
echo ""
echo "  Stop containers:"
echo "  ssh -o StrictHostKeyChecking=no $USER@$SERVER 'cd /home/fdx/dockerizer/catataphalcon && docker-compose stop'"
echo ""
echo "  SSH to server:"
echo "  ssh -o StrictHostKeyChecking=no $USER@$SERVER"
echo ""
echo "=========================================="
