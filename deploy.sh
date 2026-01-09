#!/bin/bash

# Phalcon Deployment Script
# Deploy ke: /home/fdx/dockerizer/catataphalcon

set -e  # Exit on error

echo "=========================================="
echo "🚀 Starting Phalcon Deployment"
echo "=========================================="

# Setup direktori
DEPLOY_DIR="/home/fdx/dockerizer/catataphalcon"
REPO_URL="https://github.com/nabz22/catataphalcon.git"

echo "📁 Setting up deployment directory: $DEPLOY_DIR"
mkdir -p /home/fdx/dockerizer
cd /home/fdx/dockerizer

# Clone atau update repo
if [ -d "$DEPLOY_DIR" ]; then
    echo "📥 Repository exists, updating..."
    cd "$DEPLOY_DIR"
    git pull origin main
else
    echo "📥 Cloning repository..."
    git clone "$REPO_URL" catataphalcon
    cd "$DEPLOY_DIR"
fi

echo ""
echo "✅ Repository ready"
echo "📂 Contents:"
ls -la | head -15

echo ""
echo "=========================================="
echo "🐳 Setting up Docker"
echo "=========================================="

# Check docker & docker-compose
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose not installed. Please install it first."
    exit 1
fi

echo "✅ Docker version: $(docker --version)"
echo "✅ Docker Compose version: $(docker-compose --version)"

echo ""
echo "=========================================="
echo "🔨 Building Docker images..."
echo "=========================================="

docker-compose build

echo ""
echo "=========================================="
echo "🚀 Starting containers..."
echo "=========================================="

docker-compose up -d

echo ""
echo "=========================================="
echo "✅ Deployment Complete!"
echo "=========================================="
echo ""
echo "📊 Container Status:"
docker-compose ps

echo ""
echo "🌐 Access your application:"
echo "  - App Phalcon: http://192.168.0.73:8080"
echo "  - PhpMyAdmin:  http://192.168.0.73:8090"
echo ""
echo "📝 Database Credentials:"
echo "  - Host: localhost (dari dalam container)"
echo "  - User: root / phalcon"
echo "  - Password: root / phalcon123"
echo ""
echo "📋 Useful commands:"
echo "  - View logs:  docker-compose logs -f app"
echo "  - Restart:    docker-compose restart"
echo "  - Stop:       docker-compose stop"
echo "  - Status:     docker-compose ps"
echo ""
