# LEYECO III Deployment Quick Reference

## 🔑 Your Hosting Information
- **Hosting Provider:** InterServer
- **Control Panel:** DirectAdmin
- **Panel URL:** https://my.interserver.net/
- **Domain:** wh1494404.ispot.cc
- **Installation Path:** public_html/Leyeco3_fault_report/
- **Application URL:** https://wh1494404.ispot.cc/Leyeco3_fault_report/

---

## ✅ Deployment Checklist

### Phase 1: Database Setup
- [ ] Login to DirectAdmin panel
- [ ] Create MySQL database (e.g., `username_leyeco_db`)
- [ ] Create database user (e.g., `username_leyeco_user`)
- [ ] Grant ALL PRIVILEGES to user
- [ ] Note down: DB_HOST, DB_NAME, DB_USER, DB_PASS
- [ ] Access phpMyAdmin
- [ ] Import `sql/leyeco_db.sql` file
- [ ] Verify 4 tables created (users, reports, comments, audit_logs)

### Phase 2: File Configuration
- [ ] Edit `app/config.php` with database credentials
- [ ] Update `DB_HOST` to 'localhost'
- [ ] Update `DB_NAME` with your prefixed database name
- [ ] Update `DB_USER` with your prefixed username
- [ ] Update `DB_PASS` with your password
- [ ] Update `APP_URL` to your domain
- [ ] Disable error reporting (set to 0)
- [ ] Save file

### Phase 3: Permissions
- [ ] Set `public/assets/uploads/` to 755 or 777
- [ ] Verify uploads directory exists
- [ ] Test write permissions

### Phase 4: Testing
- [ ] Visit homepage: https://wh1494404.ispot.cc/Leyeco3_fault_report/
- [ ] Test report submission
- [ ] Test report tracking
- [ ] Test file upload
- [ ] Test login page
- [ ] Login with existing user credentials
- [ ] Verify dashboard loads

### Phase 5: Security
- [ ] Change all default passwords
- [ ] Enable SSL certificate (HTTPS)
- [ ] Verify error reporting is disabled
- [ ] Test all functionality with HTTPS
- [ ] Setup regular database backups

---

## 📝 Configuration Template

Copy this into your `app/config.php` after creating your database:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'YOUR_PREFIX_leyeco_db');  // ← Replace this
define('DB_USER', 'YOUR_PREFIX_leyeco_user'); // ← Replace this
define('DB_PASS', 'YOUR_STRONG_PASSWORD');    // ← Replace this

// Application Configuration
define('APP_URL', 'https://wh1494404.ispot.cc/Leyeco3_fault_report');

// Error Reporting (Production)
error_reporting(0);
ini_set('display_errors', 0);
```

---

## 🔐 Default User Credentials

From your database, you have these users:

1. **Admin User:**
   - Email: `jaderzkiepenaranda@gmail.com`
   - Password: `password` (hashed in DB)
   - Role: ADMIN

2. **Operator User:**
   - Email: `jeric@gmail.com`
   - Password: Check your database or reset
   - Role: OPERATOR

⚠️ **IMPORTANT:** Change these passwords immediately after first login!

---

## 🌐 Important URLs

- **Homepage:** https://wh1494404.ispot.cc/Leyeco3_fault_report/
- **Submit Report:** https://wh1494404.ispot.cc/Leyeco3_fault_report/submit_report.php
- **Track Report:** https://wh1494404.ispot.cc/Leyeco3_fault_report/view_report.php
- **Login:** https://wh1494404.ispot.cc/Leyeco3_fault_report/login.php
- **Operator Dashboard:** https://wh1494404.ispot.cc/Leyeco3_fault_report/operator_dashboard.php
- **Admin Dashboard:** https://wh1494404.ispot.cc/Leyeco3_fault_report/admin_dashboard.php

---

## 🐛 Common Issues & Solutions

### "Database connection failed"
```
✓ Check DB credentials in config.php
✓ Verify database name has correct prefix
✓ Confirm user has ALL PRIVILEGES
✓ Test connection in phpMyAdmin
```

### "500 Internal Server Error"
```
✓ Check PHP version (needs 7.4+)
✓ Review DirectAdmin error logs
✓ Verify .htaccess syntax
✓ Check file permissions
```

### "Upload failed"
```
✓ Set uploads folder to 777
✓ Verify uploads directory exists
✓ Check PHP upload_max_filesize
```

### "Page not found"
```
✓ Verify APP_URL in config.php
✓ Check .htaccess RewriteBase
✓ Confirm file paths are correct
```

---

## 📞 Support Resources

- **DirectAdmin Docs:** https://docs.directadmin.com/
- **InterServer Support:** https://www.interserver.net/support/
- **PHP Documentation:** https://www.php.net/docs.php

---

## 💾 Backup Reminder

**Regular Backups:**
1. Database: Export via phpMyAdmin weekly
2. Files: Download via FTP/File Manager monthly
3. Store backups in multiple locations

---

**Deployment Date:** _____________
**Database Name:** _____________
**Database User:** _____________
**Last Backup:** _____________
