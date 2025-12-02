# 🔌 LEYECO III Trouble Report System

A modern, secure web application for managing electrical service requests and power outage reports.

## ✨ Key Features

### 👥 Public Access
- Submit and track trouble reports
- Photo uploads (JPG/PNG, max 5MB)
- Real-time status updates
- No login required for submissions

### 👷 Operator Tools
- Interactive dashboard
- Report management system
- Status workflow: NEW → INVESTIGATING → RESOLVED → CLOSED
- Full activity history

### 👑 Admin Console
- User and role management
- System analytics
- Audit logging
- Configuration settings

## � Tech Stack

- **Backend**: PHP 8.2+ with PDO
- **Database**: MySQL 8.0
- **Frontend**: Vanilla JS, HTML5, CSS3
- **Deployment**: Docker + Docker Compose
- **Web Server**: Apache 2.4

## 🚀 Quick Start

### With Docker (Recommended)
```bash
git clone [repository-url] tracking_sys
cd tracking_snys
docker-compose up -d
```

Access the application:
- Web Interface: http://localhost:8080
- phpMyAdmin: http://localhost:8081

### Default Logins
- **Admin**: admin@example.com / admin123
- **Operator**: operator@example.com / operator123

> ⚠️ **Security Note**: Change default passwords immediately after first login!

## � Project Structure

```
tracking_sys/
├── public/              # Web root
├── app/                 # Backend logic
├── sql/                 # Database files
└── docker/              # Container config
```

## 🔒 Security
- SQL injection protection
- CSRF tokens
- Secure password hashing
- Input validation & sanitization
- Role-based access control
- Comprehensive audit logging

## 📊 Database
- **users**: System users and roles
- **reports**: Service requests
- **comments**: Status updates
- **audit_logs**: System activities

Reports use format: `LEY-YYYYMMDD-####` (e.g., `LEY-20251202-0001`)

## 📝 Usage

### For Residents
1. Visit http://localhost:8080
2. Submit a new report or track existing one
3. Receive updates via email (if provided)

### For Staff
1. Log in at /login.php
2. Manage reports from dashboard
3. Update statuses and add notes

## ⚙️ Configuration
Edit `app/config.php` for:
- Database credentials
- Email settings
- System preferences

## � License
[Specify License]

---
*Built for LEYECO III - Powering Communities, One Connection at a Time*
DB_USER=leyeco_user     # Database user
DB_PASS=leyeco_pass     # Database password
APP_URL=http://localhost:8080  # Application URL
```

### Upload Settings

```php
UPLOAD_MAX_SIZE = 5MB           # Maximum file size
UPLOAD_ALLOWED_TYPES = JPG, PNG # Allowed file types
```

### Barangay List

Edit `BARANGAYS` constant in `app/config.php` to customize your area list.

## 🐳 Docker Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Rebuild containers
docker-compose up -d --build

# Access web container shell
docker-compose exec web bash

# Access database
docker-compose exec db mysql -u leyeco_user -p leyeco_db
```

## 📝 API Endpoints (Internal)

All operations are handled through PHP pages with POST requests:

- `POST /submit_report.php` - Create new report
- `POST /operator_report_view.php?id={id}` - Update report status
- `POST /manage_users.php` - User management operations

## 🧪 Testing

### Test Report Submission

1. Go to http://localhost:8080/submit_report.php
2. Fill in test data
3. Verify reference code generation
4. Check database: `SELECT * FROM reports ORDER BY id DESC LIMIT 1;`

### Test Operator Login

1. Login as operator@example.com / operator123
2. Verify dashboard displays
3. Test filtering and pagination
4. Update a report status

### Test Admin Functions

1. Login as admin@example.com / admin123
2. Create a new operator user
3. View statistics
4. Check audit logs

## 🚨 Troubleshooting

### Database Connection Error

```bash
# Check if database container is running
docker-compose ps

# View database logs
docker-compose logs db

# Restart database
docker-compose restart db
```

### Permission Errors

```bash
# Fix upload directory permissions
docker-compose exec web chmod -R 755 /var/www/html/public/assets/uploads
docker-compose exec web chown -R www-data:www-data /var/www/html/public/assets/uploads
```

### Port Already in Use

```bash
# Change ports in docker-compose.yml
# For web: change "8080:80" to "8090:80"
# For db: change "3306:3306" to "3307:3306"
```

## 📱 Browser Compatibility

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## 🔄 Updating

```bash
# Pull latest changes
git pull

# Rebuild containers
docker-compose down
docker-compose up -d --build
```

## 📄 License

This project is provided as-is for LEYECO III internal use.

## 👥 Support

For issues or questions:
- Check the troubleshooting section
- Review application logs: `docker-compose logs -f web`
- Check database logs: `docker-compose logs -f db`

## 🎉 Credits

Developed for LEYECO III (Leyte III Electric Cooperative)

---

**Version**: 1.0.0  
**Last Updated**: December 2, 2025
