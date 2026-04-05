---
description: Deploy KifahApp to Hostinger via GitHub (app.alkifahic.com)
---

# 🚀 Deployment Workflow — KifahApp to Hostinger

## Overview
- **Target URL**: https://app.alkifahic.com
- **GitHub Repo**: https://github.com/AlkifahInternational/appkifah.git
- **Server**: Hostinger (SSH access)
- **Stack**: Laravel + Livewire + Vite

---

## PHASE 1 — Local: Prepare & Push to GitHub

### Step 1: Build frontend assets locally
```bash
npm run build
```
> Generates /public/build. We include this in git for the first deploy since Hostinger shared hosting may not run Node.js.

### Step 2: Temporarily unignore public/build for first deploy
Add this exception to the bottom of .gitignore:
```
!/public/build
```

### Step 3: Commit everything and push
```bash
git add public/build
git add .gitignore
git commit -m "chore: include compiled assets for initial Hostinger deploy"
git push -u origin main
```

---

## PHASE 2 — Hostinger: SSH Access

### Step 4: Get SSH credentials
Go to: hPanel -> Advanced -> SSH Access
Enable SSH access if not already done and note your host, port, and username.

### Step 5: SSH into server
```bash
ssh username@server_ip -p PORT
```

### Step 6: Navigate to subdomain directory
```bash
cd ~/domains/app.alkifahic.com/public_html
```

### Step 7: Clone the repository
```bash
git clone https://github.com/AlkifahInternational/appkifah.git .
```

---

## PHASE 3 — Server: Install Dependencies

### Step 8: Install production dependencies
```bash
composer install --no-dev --optimize-autoloader
```

---

## PHASE 4 — Server: Configure Environment

### Step 9: Create .env file
```bash
cp .env.example .env
php artisan key:generate
```

### Step 10: Edit .env
```bash
nano .env
```
Set: APP_ENV=production, APP_DEBUG=false, APP_URL=https://app.alkifahic.com, and all DB_* values.

---

## PHASE 5 — Server: Document Root & Permissions

### Step 11: Point subdomain to /public folder
In hPanel -> Domains -> Subdomains -> app.alkifahic.com
Set Document Root to: domains/app.alkifahic.com/public_html/public

### Step 12: Fix storage permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### Step 13: Create storage symlink
```bash
php artisan storage:link
```

---

## PHASE 6 — Server: Migrate & Optimize

### Step 14: Run migrations
```bash
php artisan migrate --force
```

### Step 15: Optimize
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## PHASE 7 — Verify

### Step 16: Open https://app.alkifahic.com in browser

### Step 17: Debug if errors
```bash
tail -n 50 storage/logs/laravel.log
```

---

## Future Updates Workflow

**Local:**
```bash
npm run build
git add .
git commit -m "your message"
git push origin main
```

**Server:**
```bash
cd ~/domains/app.alkifahic.com/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```
