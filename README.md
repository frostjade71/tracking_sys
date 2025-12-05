<div align="center">

# <img src="public/assets/images/logoL3iii.webp" alt="LEYECO III Logo" height="50" style="vertical-align: middle; margin-bottom: 5px;"> **LEYECO III TROUBLE REPORT SYSTEM**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net/)
[![Docker](https://img.shields.io/badge/Docker-✓-blue.svg)](https://www.docker.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue.svg)](https://www.mysql.com/)

A web-based application developed by Computer Science Seniors at Holy Cross College of Carigara Incorporated for LEYECO III (Leyte III Electric Cooperative) as a system project in fulfillment of our On-the-Job Training/Internship requirements.

</div>

## Project Overview 
This system enables LEYECO III Member-Consumer-Owners (MCOs) to report electrical service issues and power outages through an accessible online platform. It streamlines the trouble reporting process while providing cooperative staff with efficient tools for managing, tracking, and resolving MCO complaints.

## Key Features

### Public Access
- Submit and track trouble reports
- Photo uploads (JPG/PNG, max 5MB)
- Real-time status updates
- No login required for submissions

### Operator Tools
- Interactive dashboard
- Report management system
- Status workflow: NEW → INVESTIGATING → RESOLVED → CLOSED
- Full activity history

### Admin Console
- User and role management
- System analytics
- Audit logging
- Configuration settings


## Quick Start

### With Docker (Recommended)
```bash
git clone https://github.com/frostjade71/tracking_sys tracking_sys
cd tracking_sys
docker-compose up -d
```

Access the application:
- Web Interface: http://localhost:8080
- phpMyAdmin: http://localhost:8081

### Default Logins
- **Admin**: admin@example.com / admin123
- **Operator**: operator@example.com / operator123

> **Security Note**: Change default passwords immediately after first login!

## License

This project is licensed under the MIT License. See the [LICENSE.md](LICENSE.md) file for details.

---

## **Credits** <img src="public/assets/images/HCCCI.webp" alt="HCCCI Logo" height="24" style="vertical-align: middle;"> HCCCI Computer Science Seniors

> #### Documentation & QA/Testers:
- Sophia Caridad
- Loren Mae Pascual
- Fauna Dea Opetina
- Agnes Osip
- Zxyrah Mae Indemne

> #### **Developer**
- **Jaderby Peñaranda**

  [![Website](https://img.shields.io/badge/🌏-jaderbypenaranda.link-1e88e5)](https://jaderbypenaranda.link/) [![Email](https://img.shields.io/badge/📩-Contact-4caf50)](mailto:jaderbypenaranda@gmail.com)

---

<div align="left">
<span><b>Built for</b></span>
  <img src="public/assets/images/logo_leyeco3.webp" alt="LEYECO III Logo" height="40" style="vertical-align: middle; margin-right: 12px;">
  <span><i>Lighting Houses, Lighting Homes, Lighting Hopes</i></span>
</div>

---

**Version**: 1.0.1  
**Last Updated**: December 5, 2025
