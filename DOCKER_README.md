# Phalcon Docker Setup

Panduan untuk menjalankan Phalcon project menggunakan Docker.

## Prerequisites

- Docker Desktop terinstall di sistem Anda
- Docker Compose terinstall

## Setup & Menjalankan Project

### 1. Build dan Jalankan Docker

Buka terminal/command prompt di directory project dan jalankan:

```bash
docker-compose up -d
```

Ini akan:
- Build Dockerfile untuk PHP/Apache dengan Phalcon
- Menjalankan MySQL container dengan database `test`
- Mengexpose aplikasi di http://localhost:8080

### 2. Akses Aplikasi

Buka browser dan navigate ke:
```
http://localhost:8080
```

### 3. Akses Database

Dari host machine:
```
Host: localhost
Port: 3306
Username: phalcon
Password: phalcon123
Database: test
```

Atau dari Docker container:
```
Host: db
Port: 3306
Username: phalcon
Password: phalcon123
Database: test
```

## Perintah Penting

### Melihat status container
```bash
docker-compose ps
```

### Melihat logs
```bash
docker-compose logs -f app
```

### Stop container
```bash
docker-compose stop
```

### Remove container dan volumes
```bash
docker-compose down -v
```

### Akses shell container
```bash
docker-compose exec app bash
```

### Akses MySQL dari container
```bash
docker-compose exec db mysql -uroot -proot test
```

## Konfigurasi Environment

Database configuration sudah di-update untuk membaca dari environment variables:
- `DB_HOST` (default: localhost)
- `DB_USERNAME` (default: root)
- `DB_PASSWORD` (default: '')
- `DB_NAME` (default: test)

Anda bisa mengoverride di `docker-compose.yml` environment section atau dengan file `.env`.

## Troubleshooting

### Permission denied
Jika ada error permission, jalankan:
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
```

### Port 8080 sudah terpakai
Ubah port mapping di `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Ubah 8080 menjadi port lain
```

### Cache directory tidak writable
```bash
docker-compose exec app chmod -R 775 /var/www/html/cache
```

## File-file Penting

- `Dockerfile` - Konfigurasi image untuk PHP/Apache/Phalcon
- `docker-compose.yml` - Orchestration untuk app dan database
- `.dockerignore` - File yang di-exclude saat build
- `docker/vhost.conf` - Apache virtual host configuration
