#!/bin/bash
# Server-side deployment script
# Jalankan ini di server: bash /home/fdx/deploy.sh

set -e

DEPLOY_BASE="/home/fdx/dockerizer"
REPO_URL="https://github.com/nabz22/catataphalcon.git"

echo "==========================================="
echo "🚀 STARTING SERVER-SIDE DEPLOYMENT"
echo "==========================================="
echo ""

# 1. Create base directory
echo "📁 Creating base directory..."
mkdir -p "$DEPLOY_BASE"
cd "$DEPLOY_BASE"

# 2. Clone repository
echo "📥 Cloning catataphalcon repository..."
if [ -d "catataphalcon" ]; then
    echo "  Removing old repository..."
    rm -rf catataphalcon
fi

git clone "$REPO_URL" catataphalcon
cd catataphalcon

echo "✅ Repository cloned"
echo ""

# 3. List directory contents
echo "📂 Repository contents:"
ls -la | head -20

echo ""
echo "==========================================="
echo "🐳 CHECKING DOCKER INSTALLATION"
echo "==========================================="
echo ""

docker --version
docker compose version

echo ""
echo "==========================================="
echo "🔨 BUILDING DOCKER IMAGES"
echo "==========================================="
echo ""

docker compose build --no-cache

echo ""
echo "==========================================="
echo "🚀 STARTING CONTAINERS"
echo "==========================================="
echo ""

docker compose up -d

echo ""
echo "📊 Container Status:"
docker compose ps

echo ""
echo "==========================================="
echo "✅ DEPLOYMENT COMPLETE!"
echo "==========================================="
echo ""
echo "🔗 Application URLs:"
echo "  • App: http://192.168.0.73:8080"
echo "  • PhpMyAdmin: http://192.168.0.73:8090"
echo ""
echo "💾 Database Credentials:"
echo "  • Host: db"
echo "  • User: root"
echo "  • Password: root"
echo ""
echo "📝 Next steps:"
echo "  • View logs: docker compose logs -f app"
echo "  • Restart: docker compose restart"
echo "  • Stop: docker compose stop"
echo ""
echo "==========================================="
