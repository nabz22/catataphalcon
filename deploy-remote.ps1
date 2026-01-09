# PowerShell Deployment Script untuk Server 192.168.0.73
# Deploy catataphalcon project dengan docker-compose

param(
    [string]$Server = "192.168.0.73",
    [string]$User = "fdx",
    [string]$DeployBase = "/home/fdx/dockerizer"
)

function Write-Section {
    param([string]$Title)
    Write-Host ""
    Write-Host ("=" * 42) -ForegroundColor Cyan
    Write-Host "🚀 $Title" -ForegroundColor Green
    Write-Host ("=" * 42) -ForegroundColor Cyan
    Write-Host ""
}

function Invoke-SSH {
    param(
        [string]$Command,
        [string]$Description = ""
    )
    
    if ($Description) {
        Write-Host "  $Description" -ForegroundColor Yellow
    }
    
    try {
        $output = ssh -o StrictHostKeyChecking=no "${User}@${Server}" $Command 2>&1
        Write-Host $output
        return $LASTEXITCODE -eq 0
    }
    catch {
        Write-Host "  ❌ Error: $_" -ForegroundColor Red
        return $false
    }
}

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "🚀 DEPLOYMENT SCRIPT - CATATAPHALCON" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Target: ${User}@${Server}" -ForegroundColor White
Write-Host "Deploy Path: ${DeployBase}" -ForegroundColor White
Write-Host ""

# 1. Create base directory
Write-Section "Creating Base Directory"
Invoke-SSH "mkdir -p ${DeployBase} && ls -la ${DeployBase}" -Description "Creating directory structure..."

# 2. Clone or update repository
Write-Section "Cloning/Updating Repository"

$cloneScript = @"
cd ${DeployBase}
if [ -d "catataphalcon" ]; then
    echo "  📥 Updating existing repository..."
    cd catataphalcon
    git pull origin main 2>/dev/null || git pull origin master
    echo "  ✅ Updated"
else
    echo "  📥 Cloning new repository..."
    git clone https://github.com/nabz22/catataphalcon.git catataphalcon
    echo "  ✅ Cloned"
fi
echo ""
echo "  📂 Repository contents:"
ls -la catataphalcon | head -20
"@

Invoke-SSH $cloneScript -Description "Cloning/updating catataphalcon..."

# 3. Check Docker
Write-Section "Checking Docker Installation"

$dockerCheckScript = @"
echo "Docker version:"
docker --version
echo ""
echo "Docker Compose version:"
docker-compose --version 2>/dev/null || echo "Using: docker compose (newer version)"
"@

Invoke-SSH $dockerCheckScript

# 4. Build Docker images
Write-Section "Building Docker Images"

$buildScript = @"
cd ${DeployBase}/catataphalcon
echo "Building Docker images..."
docker-compose build --no-cache
echo "✅ Build complete"
"@

Invoke-SSH $buildScript

# 5. Start containers
Write-Section "Starting Containers"

$startScript = @"
cd ${DeployBase}/catataphalcon
echo "Starting containers..."
docker-compose up -d
echo ""
echo "Container status:"
docker-compose ps
"@

Invoke-SSH $startScript

# 6. Final summary
Write-Host ""
Write-Host ("=" * 42) -ForegroundColor Cyan
Write-Host "✅ DEPLOYMENT COMPLETE!" -ForegroundColor Green
Write-Host ("=" * 42) -ForegroundColor Cyan
Write-Host ""

Write-Host "📝 DEPLOYMENT SUMMARY" -ForegroundColor Cyan
Write-Host ("=" * 42) -ForegroundColor Cyan
Write-Host ""
Write-Host "📂 Location: ${DeployBase}/catataphalcon" -ForegroundColor White
Write-Host ""
Write-Host "🔗 Access Your Applications:" -ForegroundColor Cyan
Write-Host "  • App: http://192.168.0.73:8080" -ForegroundColor White
Write-Host "  • PhpMyAdmin: http://192.168.0.73:8090" -ForegroundColor White
Write-Host ""
Write-Host "💾 Database:" -ForegroundColor Cyan
Write-Host "  • Host: db (internal)" -ForegroundColor White
Write-Host "  • User: root" -ForegroundColor White
Write-Host "  • Password: root" -ForegroundColor White
Write-Host ""
Write-Host "📋 Useful Commands:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  View logs:" -ForegroundColor White
Write-Host "  ssh ${User}@${Server} 'cd ${DeployBase}/catataphalcon && docker-compose logs -f app'" -ForegroundColor Gray
Write-Host ""
Write-Host "  Restart containers:" -ForegroundColor White
Write-Host "  ssh ${User}@${Server} 'cd ${DeployBase}/catataphalcon && docker-compose restart'" -ForegroundColor Gray
Write-Host ""
Write-Host "  Stop containers:" -ForegroundColor White
Write-Host "  ssh ${User}@${Server} 'cd ${DeployBase}/catataphalcon && docker-compose stop'" -ForegroundColor Gray
Write-Host ""
Write-Host "  SSH to server:" -ForegroundColor White
Write-Host "  ssh ${User}@${Server}" -ForegroundColor Gray
Write-Host ""
Write-Host ("=" * 42) -ForegroundColor Cyan
