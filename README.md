<div align="center">
  <h1>🚗 Vectorental - Car Rental System</h1>
  <p>A full-stack web application for car rentals, built with PHP and MySQL.</p>
  
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" />
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
</div>

## 📝 About The Project
**Vectorental** is a database-driven car rental platform designed to manage vehicles, customers, and bookings. Developed originally as a Database Final Project, it demonstrates a robust implementation of backend web architecture, relational database management, and dynamic front-end interfaces.

From a **Cybersecurity** perspective, this project serves as a foundational study in how web applications handle authentication, session management, and database queries—core concepts for understanding Web Application Security and Penetration Testing.

## ✨ Features
- **User Authentication:** Secure login and registration for customers and admins.
- **Vehicle Catalog:** Browse available cars with dynamic filtering.
- **Booking System:** Reserve vehicles and manage booking schedules.
- **Admin Dashboard:** Manage fleet inventory, view reservations, and handle customer data.
- **Relational Database:** Fully structured MySQL database (`car_rental_full.sql`) handling complex relationships.

## 🚀 Getting Started

### Prerequisites
- XAMPP / WAMP or any local server stack.
- PHP 7.x or higher
- MySQL

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/Rommana44/vectorental.git
   ```
2. Move the project folder to your local server directory (e.g., `htdocs` for XAMPP).
3. Open phpMyAdmin and create a new database named `car_rental`.
4. Import the `car_rental_full.sql` file into the database.
5. Update `customer_site/db_connect.php` with your database credentials.
6. Launch the site in your browser: `http://localhost/vectorental/customer_site`

## 🛡️ Security Considerations (Educational)
This repository is excellent for practicing web vulnerability analysis:
- **SQL Injection (SQLi):** Reviewing how inputs are sanitized before querying the database.
- **Cross-Site Scripting (XSS):** Ensuring customer inputs and car descriptions are properly encoded when rendered.
- **Broken Access Control:** Testing if unauthorized users can access the admin dashboard.

---
*Developed by Omar Romman.*
