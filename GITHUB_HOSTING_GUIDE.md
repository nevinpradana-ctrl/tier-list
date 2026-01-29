# 🚀 PANDUAN GITHUB & HOSTING - Gacha Tier List Application

## Daftar Isi
1. [Commit & Push ke GitHub](#1-commit--push-ke-github)
2. [Setup GitHub SSH (Optional)](#2-setup-github-ssh-optional)
3. [Hosting Options](#3-hosting-options)
4. [Deployment Checklist](#4-deployment-checklist)

---

## 1. Commit & Push ke GitHub

### Step 1: Buat Repository di GitHub

1. **Buka https://github.com/new**
2. **Isi form:**
   - Repository name: `tier-list` (atau nama lain sesuai keinginan)
   - Description: `Gacha Tier List Application - Laravel Web App`
   - Visibility: Public (agar bisa di-host)
   - ✅ Initialize this repository with: **DON'T** check (karena sudah ada .git)
3. **Klik "Create Repository"**

### Step 2: Salin Repository URL

Di halaman repo baru, klik tombol **Code** (hijau) dan copy URL:
- **HTTPS:** `https://github.com/username/tier-list.git`
- **SSH:** `git@github.com:username/tier-list.git`

Gunakan **HTTPS** jika belum setup SSH.

### Step 3: Tambahkan Remote Origin

```bash
cd /home/nevin/Coding/tier-list

# Ganti dengan URL repo Anda
git remote add origin https://github.com/username/tier-list.git

# Verify
git remote -v
```

### Step 4: Push ke GitHub

```bash
# Rename branch ke 'main' (standard GitHub)
git branch -M main

# Push initial commit
git push -u origin main

# Output yang diharapkan:
# Enumerating objects: XXX, done.
# Counting objects: 100% (XXX/XXX), done.
# ...
# To https://github.com/username/tier-list.git
#  * [new branch]      main -> main
# Branch 'main' set up to track remote branch 'main' from 'origin'.
```

**✅ Selesai!** Repository Anda sudah di GitHub.

---

## 2. Setup GitHub SSH (Optional)

Jika ingin commit tanpa harus input password setiap kali:

### Step 1: Generate SSH Key

```bash
ssh-keygen -t ed25519 -C "nevin@example.com"

# Tekan ENTER 3x (default settings)
# Key akan disimpan di ~/.ssh/id_ed25519
```

### Step 2: Salin Public Key

```bash
cat ~/.ssh/id_ed25519.pub

# Copy output-nya
```

### Step 3: Tambahkan ke GitHub Settings

1. Buka: https://github.com/settings/keys
2. Klik "New SSH key"
3. Title: `My Linux Machine`
4. Paste public key
5. Klik "Add SSH key"

### Step 4: Update Remote URL

```bash
cd /home/nevin/Coding/tier-list

# Ganti dari HTTPS ke SSH
git remote set-url origin git@github.com:username/tier-list.git

# Verify
git remote -v
```

### Step 5: Test Connection

```bash
ssh -T git@github.com

# Output: Hi username! You've successfully authenticated...
```

---

## 3. Hosting Options

### Option A: Railway (RECOMMENDED - Mudah & Gratis)

**Keunggulan:**
- ✅ Free tier dengan $5/bulan credits
- ✅ Deploy dari GitHub (auto-deploy)
- ✅ Support Laravel + MySQL
- ✅ Custom domain

**Langkah-langkah:**

1. **Buka https://railway.app**
2. **Sign up dengan GitHub**
3. **Create new project → Deploy from GitHub**
4. **Select repository:** tier-list
5. **Railway akan auto-detect Laravel**
6. **Configurable:**
   - Database: ✅ MySQL
   - Environment: ✅ Auto-set
7. **Deploy** - tunggu ~2-3 menit

**Setelah Deploy:**
```bash
# Jalankan migrations (via Railway CLI atau dashboard)
railway run php artisan migrate

# Seed database (optional)
railway run php artisan db:seed
```

**Akses aplikasi:** 
- URL: `https://[project-name].railway.app`
- Atau set custom domain

---

### Option B: Heroku (Legacy - Berbayar)

Heroku menghapus free tier sejak Nov 2022. Jika ingin gunakan:
- Bayar $7/month minimum

**Langkah**: Mirip Railway, tapi perlu:
1. Install Heroku CLI
2. `heroku login`
3. `heroku create tier-list`
4. `git push heroku main`

---

### Option C: Hosting Lokal (Untuk Testing)

Jika belum siap hosting online, bisa test lokal dulu:

```bash
cd /home/nevin/Coding/tier-list

# Install dependencies
composer install
npm install

# Setup .env
cp .env.example .env
php artisan key:generate

# Generate APP_KEY
php artisan key:generate

# Run migration
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Start dev server
php artisan serve

# Di terminal lain, start Vite
npm run dev

# Akses: http://localhost:8000
```

---

### Option D: VPS (Heroku-like, Lebih Kontrol)

Jika punya server sendiri (AWS, DigitalOcean, Linode):

```bash
# SSH ke server
ssh user@your-server.com

# Clone repository
git clone https://github.com/username/tier-list.git
cd tier-list

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup .env
cp .env.example .env
php artisan key:generate

# Create database
mysql -u root -p
> CREATE DATABASE tier_list CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> exit;

# Run migrations
php artisan migrate --force

# Setup web server (Nginx example)
sudo nano /etc/nginx/sites-available/tier-list
# Configure server block

# Restart Nginx
sudo systemctl restart nginx

# Setup SSL dengan Certbot
sudo certbot certonly --nginx -d your-domain.com
```

---

## 4. Deployment Checklist

Sebelum deploy ke production, pastikan:

### Local Testing
- [ ] `php artisan migrate` berjalan tanpa error
- [ ] `php artisan serve` berjalan normal
- [ ] Frontend (npm run dev) render dengan baik
- [ ] Admin login berfungsi (username: admin, password: admin)
- [ ] Test CRUD operations di admin panel
- [ ] Tier list display berfungsi

### Code Quality
- [ ] Tidak ada `dd()` atau `var_dump()` di code
- [ ] Database credentials tidak hardcoded
- [ ] `.env.example` sudah updated
- [ ] `.gitignore` mencakup: `.env`, `vendor/`, `node_modules/`, `storage/logs/`

### Configuration
- [ ] `APP_ENV=production` (setelah deploy)
- [ ] `APP_DEBUG=false` (di production)
- [ ] Database URL valid
- [ ] Storage path konfigurasi dengan benar

### Files to Upload to GitHub

✅ **Include:**
```
.env.example
.gitignore
README.md
composer.json
package.json
app/
config/
database/
resources/
routes/
storage/ (empty dir)
tests/
```

❌ **Exclude (.gitignore):**
```
.env
vendor/
node_modules/
storage/logs/
storage/app/
bootstrap/cache/
.DS_Store
```

### Database Migration

Jika hosting baru:
1. Pastikan database sudah dibuat
2. Update `.env` dengan database credentials hosting
3. Jalankan `php artisan migrate`
4. Optional: Jalankan `php artisan db:seed`

---

## 5. Quick Commands Reference

### Git Commands

```bash
# Check status
git status

# Add all files
git add .

# Commit changes
git commit -m "Deskripsi perubahan"

# Push to GitHub
git push

# Pull latest changes
git pull

# View commit history
git log --oneline

# Create new branch
git checkout -b feature/nama-fitur

# Switch branch
git checkout main

# Merge branch
git merge feature/nama-fitur
```

### Laravel Commands

```bash
# Install dependencies
composer install

# Generate APP_KEY
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear

# Serve locally
php artisan serve

# View logs
tail -f storage/logs/laravel.log
```

### Node.js Commands

```bash
# Install dependencies
npm install

# Development mode
npm run dev

# Build for production
npm run build
```

---

## 6. Troubleshooting

### Problem: "fatal: not a git repository"
**Solution:**
```bash
cd /home/nevin/Coding/tier-list
git init
git add .
git commit -m "Initial commit"
```

### Problem: Push rejected (branch ahead of master)
**Solution:**
```bash
git pull origin main
# Resolve conflicts jika ada
git push origin main
```

### Problem: Permission denied (publickey)
**Solution:**
- Setup SSH key (lihat Step 2 di atas)
- Atau gunakan Personal Access Token (PAT) dengan HTTPS

### Problem: Database error di hosting
**Solution:**
1. SSH ke server
2. Update `.env` dengan database credentials
3. Jalankan `php artisan migrate`
4. Check `storage/logs/laravel.log` untuk error detail

### Problem: 403 Forbidden di nginx
**Solution:**
```bash
# Check folder permissions
sudo chown -R www-data:www-data /path/to/tier-list
sudo chmod -R 755 storage
sudo chmod -R 755 bootstrap/cache
```

---

## 7. Recommendations

### Best Practices

✅ **DO:**
- Selalu commit dengan deskripsi yang jelas
- Gunakan branch untuk fitur baru
- Test lokal sebelum push
- Update CHANGELOG untuk setiap version
- Use meaningful commit messages

❌ **DON'T:**
- Commit `.env` file
- Push secret keys/tokens
- Force push ke main branch
- Deploy langsung tanpa testing
- Ignore error logs

### Version Control Strategy

```
main (production)
  ↑ (PR/merge only)
develop (staging)
  ↑
feature/login (feature branch)
feature/admin-panel (feature branch)
```

---

## 8. Cheat Sheet

### Ingin commit & push dengan cepat?

```bash
cd /home/nevin/Coding/tier-list

# 1. Check status
git status

# 2. Add all changes
git add .

# 3. Commit
git commit -m "feat: add tier management feature"

# 4. Push
git push origin main
```

### Ingin deploy ke Railway?

```bash
# 1. Setup repository di GitHub (done ✓)
# 2. Buka https://railway.app
# 3. Sign up dengan GitHub
# 4. Create Project → Deploy from GitHub
# 5. Select tier-list repository
# 6. Railway auto-detects & deploys (2-3 menit)
# 7. Access: https://[project].railway.app
```

### Ingin akses admin dashboard?

```
URL: https://your-hosted-url.com/admin/login
Username: admin
Password: admin
```

---

## 📞 Additional Resources

- Laravel Deployment: https://laravel.com/docs/12.x/deployment
- Railway Documentation: https://docs.railway.app
- GitHub Guide: https://guides.github.com
- Git Cheat Sheet: https://github.github.com/training-kit/

---

**Kapan pun ready untuk deploy, follow langkah-langkah di atas dan PM saya jika ada pertanyaan!** 🚀

Generated: 21 Januari 2026
