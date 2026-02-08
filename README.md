# Doctor Manager

A simple PHP/MySQL web application for managing a medical practice.  
The system supports patients, doctors, and a secretary/admin role, with appointment scheduling and medical records.

---

## Features
- User authentication with roles (Patient, Doctor, Secretary)
- Doctor availability slots (CSV upload)
- Appointment creation, update, and completion
- Patient medical records (diagnosis & treatment history)
- Basic patient and appointment management for secretary

---

## Tech Stack
- PHP
- MySQL / MariaDB
- HTML/CSS (W3.CSS)
- Python (optional, for CSV slot generation)

---

## Project Structure
```
doctor/
  ├─ index.php
  ├─ connectdb.php
  ├─ dbfunctions.php
  ├─ login.php / logout.php / register.php
  ├─ appointmentsadmin.php
  ├─ myappointments.php
  ├─ patientrecord.php
  ├─ slots.php
  ├─ doctordb.sql
```

---

## Setup
1. Create a MySQL database named `doctordb`
2. Import `doctordb.sql`
3. Update database credentials in `connectdb.php`
4. Place the project in your web root (e.g. `htdocs/doctor`)
5. Open `http://localhost/doctor`

---

## CSV Slot Format
```
YYYY-MM-DD,HH:MM
```

Example:
```
2024-10-01,09:00
2024-10-01,09:30
```

---

## Notes
- Passwords are stored in plaintext (demo only)
- No advanced security (CSRF, hashing, prepared statements)
- Intended for educational use

---

## License
No license specified.
