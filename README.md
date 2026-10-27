#  ENSA Student Project Management System

A web-based application designed to simplify the submission, management, tracking, and evaluation of student projects at the **École Nationale des Sciences Appliquées (ENSA)**.

The platform provides a centralized space for managing student projects, monitoring their status, handling project-related documents, and accessing administrative information through a user-friendly interface.

## 📌 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Technologies](#-technologies-used)
- [Project Structure](#-project-structure)
- [Prerequisites](#-prerequisites)
- [Installation and Setup](#-installation-and-setup)
- [Database Configuration](#-database-configuration)
- [How to Use](#-how-to-use)
- [Future Improvements](#-future-improvements)
- [Author](#-author)

## 🚀 Overview

Managing academic projects can involve multiple students, supervisors, submissions, documents, and administrative tasks. This application aims to centralize these activities in one web platform.

It provides functionalities for organizing student projects, managing users, following project information, and facilitating access to project-related resources.

The project was developed using PHP for server-side logic, MySQL for data storage, and HTML/CSS for the user interface.

## ✨ Features

### 📁 Project Management
- View and manage student projects.
- Access detailed project information.
- Track project statuses and submission information.
- Manage project-related documents and deliverables.
- Add and manage comments associated with projects.

### 👥 Student and User Management
- Access student information.
- Manage user records through the administrative interface.
- Organize project information by student and academic program.

### 📊 Statistics and Reporting
- Access a statistics dashboard.
- View project-related information and indicators.
- Export project information to PDF and Excel formats.

### 📄 Document Management
- Upload and store project-related files.
- Download available project documents.
- Organize deliverables associated with projects.

### 🔐 Authentication and Navigation
- Provide login, registration, and logout pages.
- Separate application pages for different user workflows.
- Navigate between project management, statistics, and user administration.

*Note: The available functionality depends on the implementation and configuration of each module.*

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Backend logic and server-side processing |
| MySQL | Relational database management |
| PDO | Database access and SQL operations |
| HTML5 | Web page structure |
| CSS3 | Styling and layout |
| JavaScript | Client-side interactions |
| Bootstrap | Responsive interface components |
| Git and GitHub | Version control and project hosting |

## 📂 Project Structure

```text
projet_ensak/
├── css/
│   ├── dashboard.css
│   ├── details.css
│   ├── etudiants.css
│   ├── profils.css
│   ├── projets.css
│   ├── style.css
│   ├── update.css
│   └── views.css
├── image/
│   ├── logo.png
│   ├── logo_ensa.png
│   └── ...
├── uploads/
│   └── Project-related documents
├── cnx.php
├── connect.php
├── connexion.php
├── inscription.php
├── dashboard.php
├── projets.php
├── projet_details.php
├── statistiques.php
├── gestion_utilisateurs.php
├── export_pdf.php
├── export_excel.php
├── download.php
├── logout.php
├── index.php
├── .gitignore
└── README.md
```

## ⚙️ Prerequisites

Before running the application, make sure you have installed:

- **PHP 8.x** or a compatible PHP version.
- **MySQL** or MariaDB.
- **Apache** web server.
- **XAMPP** or an equivalent local development environment.
- A web browser.

XAMPP is a convenient option for running PHP and MySQL locally on Windows.

## 💻 Installation and Setup

### 1. Clone the repository

Open a terminal and run:

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
```

Replace `YOUR_USERNAME` and `YOUR_REPOSITORY` with your GitHub details.

### 2. Move the project into your web server directory

For XAMPP, place the project folder inside:

```text
C:\xampp\htdocs\projet_ensak
```

### 3. Start the required services

Open the XAMPP Control Panel and start:

- Apache
- MySQL

Make sure the MySQL port matches your database configuration.

### 4. Create the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a database named:

```sql
CREATE DATABASE projet
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Import the project's database schema and create the tables required by the application.

**Important:** The database schema must be available before the application can run correctly. Include a verified SQL export, such as `database/schema.sql`, in the repository to make setup reproducible.

### 5. Configure the database connection

Open `cnx.php` and verify the connection settings for your local environment.

The current configuration uses:

```php
$host = 'localhost';
$dbname = 'projet';
$username = 'root';
$password = '';
$port = '3308';
```

Adjust the credentials and port to match your MySQL installation. Use environment variables or another secure configuration method for production deployments rather than committing credentials.

Ensure that all PHP files use a consistent database connection.

### 6. Launch the application

Open your browser and navigate to:

```text
http://localhost/projet_ensak/
```

If the application does not load correctly, check the database configuration, required PHP extensions, Apache error logs, and the database tables.

## 🧭 How to Use

1. Start the application in your local development environment.
2. Open the appropriate login or registration page.
3. Navigate to the project management section.
4. Browse project details and available documents.
5. Use the administrative pages to access user information and statistics.
6. Export supported project information when needed.

The exact actions available depend on the account type, implemented permissions, and database configuration.

## 🔮 Future Improvements

Potential improvements include:

- Implementing consistent role-based access control for students, professors, supervisors, and administrators.
- Strengthening authentication and authorization.
- Improving database consistency and validation.
- Adding automated email notifications for project status changes.
- Developing advanced search, filtering, and pagination.
- Adding automated tests and deployment workflows.
- Improving responsive design and accessibility.
- Documenting the database schema and providing sample data for easier setup.

## 🔒 Security and Privacy

- Never commit database passwords, API keys, or other secrets.
- Do not publish private student documents or personal information.
- Validate uploaded files and restrict permitted file types and sizes.
- Protect sensitive pages and file downloads with appropriate authorization checks.
- Configure production credentials securely.

## 👩‍💻 Author

**Project:** ENSA Student Project Management System

**Institution:** École Nationale des Sciences Appliquées (ENSA)

Developed as an academic web development project.

---

⭐ If you find this project useful, feel free to explore the source code and suggest improvements.

