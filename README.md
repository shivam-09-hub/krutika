# EduManage Pro — Student Management System

A modern, SaaS-grade **Student Management System** built with **Core PHP**, **MySQL (via XAMPP)**, **HTML5**, and a custom **Modern CSS3 Design System**. Specially designed for BCA / MCA college project viva demonstrations.

---

## 📸 Key Features

- 🔐 **Executive Authentication**:
  - Split-screen modern design with glassmorphic login card.
  - Quick-fill 1-click button for instant viva demo (`admin` / `admin123`).
  - Session-based access control.
- 📊 **Interactive Academic Dashboard**:
  - 4 Key Performance Indicator (KPI) Metric Cards (Total Students, Active Courses, Departments, Academic Year).
  - Dynamic time-based greetings ("Good morning / afternoon / evening, Admin").
  - Real-time Department Distribution progress bars with percentage breakdown.
  - Gender demographic breakdown indicators.
  - Recent student enrollment feed with custom initial avatar badges.
- 👥 **Comprehensive Student Directory**:
  - Multi-criteria real-time search (by Name, Roll Number, Email).
  - Dynamic filtering by Academic Department and Degree Course.
  - One-click Printable Roster (`window.print()` formatted for academic grading).
  - Monospace roll number pills and status badges.
- ➕ **Structured Student Registration**:
  - Two-column responsive layout separated into *Personal Information* and *Academic & Institutional Details*.
  - Inline SVG field icons, live validation, and duplicate roll number protection.
- ✏️ **Profile Modification & Safety Deletion**:
  - Safe editing with conflict avoidance.
  - Dedicated danger safety dialog with student summary before permanent deletion.

---

## 🛠️ Technology Stack

- **Frontend**: HTML5, Modern CSS3 (CSS Variables, Flexbox, CSS Grid, Glassmorphism, Google Font *Plus Jakarta Sans* & *JetBrains Mono*).
- **Backend**: Core PHP 8.x (No frameworks, 100% beginner-friendly and clean).
- **Database**: MySQL via phpMyAdmin (XAMPP).
- **Icons**: Handcrafted inline SVG icons (no external heavy libraries needed).

---

## 🚀 How to Run Locally

1. Launch **XAMPP Control Panel** and ensure **Apache** and **MySQL** are running.
2. Ensure this project folder is inside `C:\xampp\htdocs\Krutika\student_management\`.
3. Open **phpMyAdmin** at `http://localhost/phpmyadmin/`.
4. Import or execute the SQL queries from `database/schema.sql`.
5. Open your web browser and navigate to:
   ```
   http://localhost/Krutika/student_management/
   ```
6. Click **"Quick Fill"** on the login page or enter:
   - **Username**: `admin`
   - **Password**: `admin123`

---

## 📁 Directory Structure

```
student_management/
├── index.php              → Entry point redirecting to login.php
├── login.php              → Modern split-screen authentication portal
├── logout.php             → Destroys admin session and redirects
├── dashboard.php          → Real-time KPI metrics, charts & recent enrollments
├── students.php           → Full student directory with search, filters & print
├── add_student.php        → Structured student enrollment form
├── edit_student.php       → Pre-filled profile update editor
├── delete_student.php     → Safety confirmation dialog with student card
├── config/
│   └── database.php       → MySQLi database connection
├── includes/
│   ├── header.php         → Dark obsidian sidebar navigation & sticky topbar
│   └── footer.php         → HTML closing tags
├── assets/
│   └── css/
│       └── style.css      → Enterprise design system stylesheet
├── database/
│   └── schema.sql         → Database creation & sample records script
└── README.md              → Project documentation for viva
```
