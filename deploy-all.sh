#!/bin/bash

# Master Deployment Script untuk Multiple Projects
# Deploy ke: /home/fdx/dockerizer/
# Projects: catataphalcon, notes-app

set -e  # Exit on error

echo "=========================================="
echo "🚀 Starting Multi-Project Deployment"
echo "=========================================="

# Base deployment directory
DEPLOY_BASE="/home/fdx/dockerizer"

# Projects array
declare -a PROJECTS=(
    "catataphalcon"
    "notes-app"
)

# GitHub repositories (update sesuai repo Anda)
declare -A REPOS=(
    [catataphalcon]="https://github.com/nabz22/catataphalcon.git"
    [notes-app]="https://github.com/nabz22/catataphalcon.git"  # dari folder yang sama
)

echo "📁 Setting up base deployment directory: $DEPLOY_BASE"
mkdir -p "$DEPLOY_BASE"
cd "$DEPLOY_BASE"

# Deploy each project
for PROJECT in "${PROJECTS[@]}"; do
    echo ""
    echo "=========================================="
    echo "📦 Deploying: $PROJECT"
    echo "=========================================="
    
    PROJECT_DIR="$DEPLOY_BASE/$PROJECT"
    
    if [ -d "$PROJECT_DIR" ]; then
        echo "📥 Repository exists, updating..."
        cd "$PROJECT_DIR"
        git pull origin main || git pull origin master
    else
        echo "📥 Cloning repository..."
        git clone "${REPOS[$PROJECT]}" "$PROJECT_DIR"
        cd "$PROJECT_DIR"
    fi
    
    echo "✅ $PROJECT repository ready"
    echo ""
done

echo ""
echo "=========================================="
echo "🐳 Checking Docker Installation"
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
echo "🔨 Building & Starting Docker Containers"
echo "=========================================="

# Deploy dan start setiap project
for PROJECT in "${PROJECTS[@]}"; do
    PROJECT_DIR="$DEPLOY_BASE/$PROJECT"
    
    if [ -f "$PROJECT_DIR/docker-compose.yml" ]; then
        echo ""
        echo "🐳 Processing $PROJECT..."
        cd "$PROJECT_DIR"
        
        echo "  Building images..."
        docker-compose build
        
        echo "  Starting containers..."
        docker-compose up -d
        
        echo "  ✅ $PROJECT deployed successfully"
    else
        echo "⚠️  No docker-compose.yml found in $PROJECT, skipping..."
    fi
done

echo ""
echo "=========================================="
echo "✅ Deployment Complete!"
echo "=========================================="
echo ""

# Show status of all projects
for PROJECT in "${PROJECTS[@]}"; do
    PROJECT_DIR="$DEPLOY_BASE/$PROJECT"
    
    if [ -f "$PROJECT_DIR/docker-compose.yml" ]; then
        echo "📊 Status for $PROJECT:"
        cd "$PROJECT_DIR"
        docker-compose ps
        echo ""
    fi
done

echo "=========================================="
echo "📝 DEPLOYMENT SUMMARY"
echo "=========================================="
echo ""
echo "📂 Deployment Directory: $DEPLOY_BASE"
echo ""
echo "🔗 Project Access URLs:"
echo "  - CatataPhalcon App: http://192.168.0.73:8080"
echo "  - CatataPhalcon DB:  http://192.168.0.73:8090 (PhpMyAdmin)"
echo ""
echo "📋 Useful Commands:"
echo ""
echo "  View logs:"
echo "    cd $DEPLOY_BASE/catataphalcon && docker-compose logs -f app"
echo ""
echo "  Restart specific project:"
echo "    cd $DEPLOY_BASE/catataphalcon && docker-compose restart"
echo ""
echo "  Stop specific project:"
echo "    cd $DEPLOY_BASE/catataphalcon && docker-compose stop"
echo ""
echo "  View containers:"
echo "    docker ps"
echo ""
echo "=========================================="
