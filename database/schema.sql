-- ============================================
-- Student Management System - Database Schema
-- ============================================
-- Run this script in phpMyAdmin to create
-- the database and the students table.
-- ============================================

-- Step 1: Create the database
CREATE DATABASE IF NOT EXISTS student_management;

-- Step 2: Select (use) the database
USE student_management;

-- Step 3: Create the students table
CREATE TABLE IF NOT EXISTS students (
    id          INT AUTO_INCREMENT PRIMARY KEY,   -- Unique Student ID (auto-generated)
    full_name   VARCHAR(100) NOT NULL,            -- Full name of the student
    roll_number VARCHAR(20)  NOT NULL UNIQUE,     -- Roll number (must be unique)
    email       VARCHAR(100) NOT NULL,            -- Email address
    phone       VARCHAR(15)  NOT NULL,            -- Phone number
    course      VARCHAR(50)  NOT NULL,            -- Course (e.g., BCA, MCA)
    department  VARCHAR(50)  NOT NULL,            -- Department name
    semester    INT          NOT NULL,            -- Current semester (1-8)
    gender      ENUM('Male', 'Female', 'Other') NOT NULL,  -- Gender
    admission_date DATE     NOT NULL,            -- Date of admission
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Record creation timestamp
);

-- Step 4: Insert some sample students for testing
INSERT INTO students (full_name, roll_number, email, phone, course, department, semester, gender, admission_date) VALUES
('Aarav Sharma',    'BCA2024001', 'aarav.sharma@email.com',    '9876543210', 'BCA', 'Computer Science', 3, 'Male',   '2024-07-15'),
('Priya Patel',     'BCA2024002', 'priya.patel@email.com',     '9876543211', 'BCA', 'Computer Science', 3, 'Female', '2024-07-15'),
('Rahul Verma',     'MCA2024001', 'rahul.verma@email.com',     '9876543212', 'MCA', 'Information Technology', 1, 'Male',   '2024-08-01'),
('Sneha Gupta',     'BCA2024003', 'sneha.gupta@email.com',     '9876543213', 'BCA', 'Computer Science', 5, 'Female', '2023-07-10'),
('Amit Kumar',      'BSC2024001', 'amit.kumar@email.com',      '9876543214', 'B.Sc IT', 'Information Technology', 1, 'Male',   '2024-08-01');
