# LEYECO III Utility Report System - Quick Start Guide

## 🚀 One-Command Deployment

```bash
cd c:\xampp\htdocs\tracking_sys
docker-compose up -d
```

Wait 30-60 seconds for database initialization, then access:

- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081 (root / root_password)

## 🔑 Default Login Credentials

### Admin Account
- **Email**: admin@example.com
- **Password**: admin123
- **Access**: Full system control, user management, all reports

### Operator Account
- **Email**: operator@example.com
- **Password**: operator123
- **Access**: View and manage reports, update status, assign technicians

> ⚠️ **IMPORTANT**: Change these passwords immediately after first login!

## 📝 Quick Test Flow

### 1. Submit a Public Report (No Login)

1. Go to http://localhost:8080
2. Click **"Submit New Report"**
3. Fill in the form:
   - Type: Power Outage
   - Description: "No power in our area since 3 PM"
   - Barangay: Select any
   - Address: "123 Main Street"
   - (Optional) Upload a photo
4. Click **"Submit Report"**
5. **Save the reference code** (e.g., LEY-20251202-0001)

### 2. Track Your Report (No Login)

1. Go to homepage
2. Enter your reference code in "Track Your Report"
3. Click **"View Report"**
4. See your report details and status

### 3. Manage as Operator

1. Go to http://localhost:8080/login.php
2. Login as **operator@example.com / operator123**
3. See your report in the dashboard
4. Click **"View"** on the report
5. Update status to **"INVESTIGATING"**
6. Add a comment: "Technician dispatched to the area"
7. Assign to yourself
8. Go back to public view - see the updates!

### 4. Admin Functions

1. Login as **admin@example.com / admin123**
2. View statistics on admin dashboard
3. Go to **"Manage Users"**
4. Create a new operator:
   - Name: Test Operator
   - Email: test@example.com
   - Password: test123
   - Role: OPERATOR
5. Verify new user can login

## 🛠️ Common Commands

### Start the Application
```bash
docker-compose up -d
```

### Stop the Application
```bash
docker-compose down
```

### View Logs
```bash
docker-compose logs -f
```

### Restart Services
```bash
docker-compose restart
```

### Access Database
```bash
docker-compose exec db mysql -u leyeco_user -p leyeco_db
# Password: leyeco_pass
```

### Check Running Containers
```bash
docker-compose ps
```

## 📁 Important Files

- **Configuration**: `app/config.php`
- **Database Schema**: `sql/schema.sql`
- **Default Users**: `sql/seed.sql`
- **Homepage**: `public/homepage.php`
- **Submit Report**: `public/submit_report.php`
- **Operator Dashboard**: `public/operator_dashboard.php`
- **Admin Dashboard**: `public/admin_dashboard.php`

## 🔧 Customization

### Change Barangay List
Edit `app/config.php`, find `BARANGAYS` constant:

```php
define('BARANGAYS', [
    'Your Barangay 1',
    'Your Barangay 2',
    // ... add more
]);
```

### Change Upload Limits
Edit `app/config.php`:

```php
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
```

### Change Session Lifetime
Edit `app/config.php`:

```php
define('SESSION_LIFETIME', 3600 * 8); // 8 hours
```

## 🐛 Troubleshooting

### Port Already in Use
Edit `docker-compose.yml` and change ports:
```yaml
ports:
  - "8090:80"  # Change 8080 to 8090
```

### Database Connection Error
```bash
# Restart database container
docker-compose restart db

# Check logs
docker-compose logs db
```

### Permission Errors
```bash
# Fix upload directory permissions
docker-compose exec web chmod -R 755 /var/www/html/public/assets/uploads
```

### Clear All Data and Restart
```bash
docker-compose down -v
docker-compose up -d
```

## 📊 Report Status Flow

1. **NEW** - Just submitted
2. **INVESTIGATING** - Operator is working on it
3. **RESOLVED** - Issue fixed
4. **CLOSED** - Report archived

## 🎯 Next Steps

1. **Change default passwords**
2. **Customize barangay list**
3. **Add your logo** to `public/assets/images/`
4. **Test all features** thoroughly
5. **Deploy to production** server

## 📞 Emergency Contact

For urgent electrical issues, call: **123-4567**

---

**System Ready!** 🎉

Access your application at: **http://localhost:8080**
