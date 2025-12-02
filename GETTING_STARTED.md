# 🚀 LEYECO III Deployment - Getting Started

## 📦 What You Have

You've successfully uploaded your files to:
- **Server Path:** `domains > wh1494404.ispot.cc > public_html > Leyeco3_fault_report`
- **Web URL:** https://wh1494404.ispot.cc/Leyeco3_fault_report/

## ⚡ Quick Start Guide (3 Main Steps)

### Step 1: Setup Database (15 minutes)
1. Login to DirectAdmin: https://my.interserver.net/
2. Go to **MySQL Management**
3. Create database: `leyeco_db` (note the full name with prefix)
4. Create user: `leyeco_user` (note the full name with prefix)
5. Grant ALL PRIVILEGES to the user
6. Open **phpMyAdmin**
7. Select your database
8. Click **Import** tab
9. Upload file: `sql/leyeco_db.sql` from your local computer
10. Click **Go** to import

**✅ Result:** You should see 4 tables created (users, reports, comments, audit_logs)

---

### Step 2: Update Configuration (5 minutes)

**Option A: Use the production template**
1. In DirectAdmin File Manager, navigate to: `public_html/Leyeco3_fault_report/app/`
2. Rename `config.production.php` to `config.php` (backup the old one first)
3. Edit `config.php` and replace:
   - `YOUR_PREFIX_leyeco_db` → your actual database name
   - `YOUR_PREFIX_leyeco_user` → your actual username
   - `YOUR_STRONG_PASSWORD` → your actual password

**Option B: Edit existing config.php**
1. Open: `public_html/Leyeco3_fault_report/app/config.php`
2. Find lines 14-18 and update:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'your_actual_db_name');  // ← Change this
   define('DB_USER', 'your_actual_username'); // ← Change this
   define('DB_PASS', 'your_actual_password'); // ← Change this
   ```
3. Find line 23 and update:
   ```php
   define('APP_URL', 'https://wh1494404.ispot.cc/Leyeco3_fault_report');
   ```
4. Find lines 7-9 and change to:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```

**✅ Result:** Configuration file is ready for production

---

### Step 3: Set Permissions & Test (10 minutes)

1. **Set Upload Folder Permissions:**
   - Navigate to: `public_html/Leyeco3_fault_report/public/assets/uploads/`
   - Right-click → Change Permissions
   - Set to: **755** (or 777 if 755 doesn't work)

2. **Test Database Connection:**
   - Upload `test_db.php` to: `public_html/Leyeco3_fault_report/`
   - Edit the file and add your database credentials
   - Visit: https://wh1494404.ispot.cc/Leyeco3_fault_report/test_db.php
   - Check if connection is successful
   - **DELETE the file** after testing!

3. **Test Your Application:**
   - Visit: https://wh1494404.ispot.cc/Leyeco3_fault_report/
   - You should see the homepage
   - Try submitting a test report
   - Try logging in at: https://wh1494404.ispot.cc/Leyeco3_fault_report/login.php

**✅ Result:** Application is live and working!

---

## 🔐 Default Login Credentials

From your database export, you have:

**Admin Account:**
- Email: `jaderzkiepenaranda@gmail.com`
- Password: `password` (the actual hashed password is in your database)

**Operator Account:**
- Email: `jeric@gmail.com`
- Password: Check your database or reset it

⚠️ **IMPORTANT:** Change these passwords immediately after first login!

---

## 📁 Files You Need to Upload/Update

### Required Files Already Uploaded ✅
- All PHP files in `public/` folder
- All files in `app/` folder
- All assets (CSS, JS, images)

### Files to Update on Server
1. **`app/config.php`** - Update database credentials
2. **`public/.htaccess`** - Copy from `.htaccess.production` (optional)
3. **`test_db.php`** - Upload temporarily for testing, then delete

### Files NOT Needed on Server
- `docker/` folder (Docker is for local development only)
- `docker-compose.yml`
- `.env.example`
- `.git/` folder

---

## 🎯 Testing Checklist

After deployment, test these features:

- [ ] Homepage loads: https://wh1494404.ispot.cc/Leyeco3_fault_report/
- [ ] Submit a report (with photo upload)
- [ ] Track a report using reference code
- [ ] Login page works
- [ ] Operator dashboard displays
- [ ] Admin dashboard displays
- [ ] Can update report status
- [ ] Can add comments to reports
- [ ] Can manage users (admin only)
- [ ] File uploads work correctly

---

## 🆘 Troubleshooting

### "Database connection failed"
→ Double-check credentials in `app/config.php`
→ Verify database name includes the correct prefix
→ Use `test_db.php` to diagnose the issue

### "500 Internal Server Error"
→ Check DirectAdmin error logs
→ Verify PHP version is 7.4 or higher
→ Check `.htaccess` file syntax

### "Cannot upload files"
→ Set uploads folder to 777 permissions
→ Verify the folder exists: `public/assets/uploads/`

### "Page not found" or broken links
→ Update `APP_URL` in `config.php`
→ Check `.htaccess` file is present

---

## 📚 Documentation Files

I've created these helpful files for you:

1. **`.agent/workflows/deploy-to-hosting.md`** - Complete step-by-step deployment guide
2. **`DEPLOYMENT_CHECKLIST.md`** - Quick reference checklist
3. **`app/config.production.php`** - Production-ready config template
4. **`public/.htaccess.production`** - Production .htaccess file
5. **`test_db.php`** - Database connection test script

---

## 🔒 Security Reminders

1. ✅ Change all default passwords
2. ✅ Enable SSL/HTTPS on your domain
3. ✅ Disable error reporting in production (already done in config)
4. ✅ Delete `test_db.php` after testing
5. ✅ Setup regular database backups
6. ✅ Keep your hosting panel password secure

---

## 📞 Need Help?

If you get stuck:

1. **Check the detailed guide:** `.agent/workflows/deploy-to-hosting.md`
2. **Use the test script:** Upload and run `test_db.php`
3. **Check DirectAdmin logs:** Look for error messages
4. **Verify database:** Use phpMyAdmin to check tables
5. **Contact InterServer support:** They can help with server-specific issues

---

## ✨ You're Almost There!

Just follow the 3 main steps above, and your application will be live!

**Estimated Time:** 30 minutes total

Good luck! 🚀
