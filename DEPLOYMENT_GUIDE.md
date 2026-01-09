# 🚀 DEPLOYMENT GUIDE - CATATAPHALCON PROJECT

## 📋 Requirements Sebelum Deploy

✅ Server: 192.168.0.73 (Linux/Ubuntu)
✅ SSH Access: user `fdx` dengan password `k2Zd2qS2j`
✅ Docker & Docker Compose terinstall di server
✅ Git terinstall di server
✅ Direktori `/home/fdx/dockerizer` untuk deployment

## 🎯 Struktur Deployment

```
/home/fdx/dockerizer/
├── catataphalcon/          # Main Phalcon app
│   ├── app/                # Application code
│   ├── public/             # Public assets
│   ├── docker-compose.yml  # Docker Compose config
│   ├── Dockerfile          # Docker image config
│   └── ...
│
└── notes-app/              # Notes app (subfolder)
    ├── public/
    ├── docker-compose.yml
    ├── Dockerfile
    └── ...
```

## 📝 Deployment Steps

### Step 1: Koneksi SSH ke Server

```bash
ssh -o StrictHostKeyChecking=no fdx@192.168.0.73
# Password: k2Zd2qS2j
```

### Step 2: Buat Direktori Deploy

```bash
mkdir -p /home/fdx/dockerizer
cd /home/fdx/dockerizer
```

### Step 3: Clone Repository CatataPhalcon

```bash
git clone https://github.com/nabz22/catataphalcon.git catataphalcon
cd catataphalcon
```

### Step 4: Verifikasi Docker

```bash
docker --version
docker-compose --version
```

### Step 5: Build Docker Images

```bash
# Dari dalam direktori catataphalcon
docker-compose build --no-cache
```

### Step 6: Start Containers

```bash
# Start containers in background
docker-compose up -d

# Check status
docker-compose ps
```

### Step 7: Verifikasi Application

Akses aplikasi via browser:
- **App**: http://192.168.0.73:8080
- **PhpMyAdmin**: http://192.168.0.73:8090

## 🔑 Database Credentials

```
Host: db (atau localhost dari container lain)
Username: root
Password: root
Database: notes_db
```

## 📋 Useful Commands (Dari Local/Remote)

### View Logs

```bash
# From local machine
ssh fdx@192.168.0.73 'cd /home/fdx/dockerizer/catataphalcon && docker-compose logs -f app'

# Atau SSH ke server dulu
ssh fdx@192.168.0.73
cd /home/fdx/dockerizer/catataphalcon
docker-compose logs -f app
```

### Restart Containers

```bash
cd /home/fdx/dockerizer/catataphalcon
docker-compose restart
```

### Stop Containers

```bash
cd /home/fdx/dockerizer/catataphalcon
docker-compose stop
```

### Start Containers

```bash
cd /home/fdx/dockerizer/catataphalcon
docker-compose start
```

### Remove Containers & Volumes

```bash
cd /home/fdx/dockerizer/catataphalcon
docker-compose down -v
```

### View Running Containers

```bash
docker ps
```

### Pull Latest Code & Redeploy

```bash
cd /home/fdx/dockerizer/catataphalcon
git pull origin main
docker-compose build --no-cache
docker-compose restart
```

## 🐳 Docker Compose Configuration

File: `docker-compose.yml` di root catataphalcon

**Services:**
1. **app** (phalcon-app)
   - Port: 8080:80
   - Image: Build dari Dockerfile
   - Volumes: app/, public/, cache/

2. **db** (phalcon-db)
   - Port: 3306:3306
   - Image: mysql:8.0
   - Root Password: root

3. **phpmyadmin** (phalcon-phpmyadmin)
   - Port: 8090:80
   - Access database management

## ⚠️ Troubleshooting

### Port 8080 sudah digunakan

```bash
# Check apa yang pakai port 8080
netstat -tlnp | grep 8080

# Ganti port di docker-compose.yml
# Ubah "8080:80" menjadi "8082:80" (atau port lain yang available)
docker-compose restart
```

### Database Connection Error

```bash
# Cek status database container
docker-compose ps

# Check logs
docker-compose logs db

# Restart database
docker-compose restart db
```

### Build Error

```bash
# Clean build
docker-compose build --no-cache --pull

# Check Dockerfile dan dependencies
```

## 🔄 Update Deployment

Jika ada changes di repository:

```bash
cd /home/fdx/dockerizer/catataphalcon
git pull origin main
docker-compose build --no-cache
docker-compose up -d
```

## 📊 Monitoring

### Check container resources

```bash
docker stats
```

### Check disk usage

```bash
docker system df
```

### View all images

```bash
docker images
```

### View all containers (termasuk yang stopped)

```bash
docker ps -a
```

## 🔐 Security Notes

- Default password untuk database: `root`
- PhpMyAdmin accessible on port 8090
- Consider mengubah password production
- Setup firewall rules untuk port 8080, 8090

## 📞 Support Commands

```bash
# Get into container
docker exec -it phalcon-app bash

# Get into database container
docker exec -it phalcon-db mysql -u root -p

# View container IP
docker inspect phalcon-app | grep IPAddress

# View environment variables
docker inspect phalcon-app
```

---

**Last Updated**: January 2025
**Project**: CatataPhalcon - CRUD Notes Application
**Deployment Method**: Docker Compose on 192.168.0.73
