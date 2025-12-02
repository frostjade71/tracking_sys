# LEYECO III Utility Report System

A complete, production-ready PHP + MySQL web application for managing electrical trouble reports with public submission, operator management, and admin controls.

## 🚀 Features

### Public Features (No Login Required)
- **Submit Trouble Reports**: Report power outages, damaged equipment, or electrical hazards
- **Track Reports**: View report status using unique reference codes
- **Photo Upload**: Attach photos to reports (max 5MB, JPG/PNG only)
- **Real-time Statistics**: View system-wide report statistics

### Operator Features (Login Required)
- **Dashboard**: View all reports with advanced filtering
- **Report Management**: Update status, add notes, assign technicians
- **Status Tracking**: NEW → INVESTIGATING → RESOLVED → CLOSED
- **Activity Timeline**: Complete history of all report updates

### Admin Features (Admin Role Only)
- **User Management**: Add, edit, delete operators and admins
- **System Metrics**: Comprehensive statistics and analytics
- **Role Management**: Assign ADMIN or OPERATOR roles
- **Audit Logs**: Track all system activities

## 📋 Technology Stack

- **Backend**: PHP 8.2+ (OOP with PDO)
- **Database**: MySQL 8.0
- **Server**: Apache 2.4
- **Frontend**: Pure PHP templates + HTML/CSS + Vanilla JS
- **Deployment**: Docker + Docker Compose

## 🛠️ Installation

### Prerequisites

- Docker Desktop installed
- Docker Compose installed
- Git (optional)

### Quick Start

1. **Clone or download the project**
   ```bash
   cd c:\xampp\htdocs\tracking_sys
   ```

2. **Start the application**
   ```bash
   docker-compose up -d
   ```

3. **Wait for services to initialize** (30-60 seconds)
   The database will automatically create tables and seed default users.

4. **Access the application**
   - **Main Application**: http://localhost:8080
   - **phpMyAdmin**: http://localhost:8081 (root / root_password)

5. **Login with default credentials**
   - **Admin**: admin@example.com / admin123
   - **Operator**: operator@example.com / operator123

   ⚠️ **IMPORTANT**: Change these passwords immediately after first login!

### Manual Installation (Without Docker)

1. **Set up MySQL database**
   ```sql
   CREATE DATABASE leyeco_db;
   CREATE USER 'leyeco_user'@'localhost' IDENTIFIED BY 'leyeco_pass';
   GRANT ALL PRIVILEGES ON leyeco_db.* TO 'leyeco_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Import database schema**
   ```bash
   mysql -u leyeco_user -p leyeco_db < sql/schema.sql
   mysql -u leyeco_user -p leyeco_db < sql/seed.sql
   ```

3. **Configure Apache**
   - Set DocumentRoot to `/path/to/tracking_sys/public`
   - Enable mod_rewrite
   - Copy `docker/apache.conf` settings to your virtual host

4. **Update configuration**
   - Edit `app/config.php` with your database credentials

5. **Set permissions**
   ```bash
   chmod -R 755 public/assets/uploads
   ```

## 📁 Project Structure

```
tracking_sys/
├── public/                      # Public web root
│   ├── index.php               # Entry point (redirects to homepage)
│   ├── homepage.php            # Landing page
│   ├── homepage.css            # Homepage styles
│   ├── submit_report.php       # Public report submission
│   ├── view_report.php         # Public report viewing
│   ├── login.php               # Staff login
│   ├── logout.php              # Logout handler
│   ├── operator_dashboard.php  # Operator dashboard
│   ├── operator_report_view.php # Report management
│   ├── admin_dashboard.php     # Admin dashboard
│   ├── manage_users.php        # User management
│   ├── .htaccess               # Apache configuration
│   └── assets/
│       ├── css/
│       │   └── dashboard.css   # Dashboard styles
│       ├── js/                 # JavaScript files
│       ├── images/             # Static images
│       └── uploads/            # User-uploaded photos
├── app/                        # Application logic
│   ├── config.php              # Configuration & constants
│   ├── db.php                  # PDO database connection
│   ├── functions.php           # Helper functions
│   ├── AuthController.php      # Authentication logic
│   ├── ReportController.php    # Report CRUD operations
│   └── UserController.php      # User management
├── sql/                        # Database files
│   ├── schema.sql              # Database schema
│   └── seed.sql                # Default users
├── docker/                     # Docker configuration
│   ├── Dockerfile              # PHP Apache image
│   └── apache.conf             # Apache virtual host
├── docker-compose.yml          # Multi-container setup
├── .env.example                # Environment template
└── README.md                   # This file
```

## 🔒 Security Features

- ✅ **PDO Prepared Statements**: SQL injection protection
- ✅ **CSRF Tokens**: Cross-site request forgery protection
- ✅ **Password Hashing**: bcrypt with `password_hash()`
- ✅ **Input Validation**: Sanitization on all user inputs
- ✅ **File Upload Validation**: Type, size, and MIME checks
- ✅ **Session Security**: Secure session management
- ✅ **Role-Based Access**: Operator and Admin permissions
- ✅ **Audit Logging**: Track all system activities

## 📊 Database Schema

### Tables

1. **users** - System users (operators and admins)
2. **reports** - Trouble reports with all details
3. **comments** - Status updates and operator notes
4. **audit_logs** - System activity tracking

### Reference Code Format

Reports are assigned unique reference codes: `LEY-YYYYMMDD-####`

Example: `LEY-20251202-0001`

## 🎯 Usage Guide

### Submitting a Report (Public)

1. Go to http://localhost:8080
2. Click "Submit New Report"
3. Fill in the form (only description, type, barangay, and address are required)
4. Optionally upload a photo
5. Submit and save your reference code

### Tracking a Report (Public)

1. Go to homepage
2. Enter your reference code in the "Track Your Report" section
3. View status and history

### Managing Reports (Operator)

1. Login at http://localhost:8080/login.php
2. View all reports in the dashboard
3. Click "View" on any report
4. Update status, add notes, or assign technicians

### Managing Users (Admin)

1. Login as admin
2. Navigate to "Manage Users"
3. Add new users with name, email, password, and role
4. Change roles or delete users as needed

## 🔧 Configuration

### Environment Variables

Edit `app/config.php` or set environment variables:

```php
DB_HOST=db              # Database host
DB_NAME=leyeco_db       # Database name
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
