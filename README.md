# 📬 Messaging System (PHP + MySQL)

This is a web-based **Messaging System** built using **PHP**, **MySQL**, and **HTML/CSS**. It supports secure user registration/login with password encryption, sending/editing messages, and logging all activities with SQL triggers.

---

## 📁 Folder Structure
```
C:\xampp\htdocs\MESSAGING-SYSTEM
│
├── database\ # SQL files for database setup
│
├── delete-acc\ # Account deletion page
│
├── home\ # User dashboard/homepage
│
├── login\ # Login system with password encryption
│
├── main\ # Main messaging system (send/edit/view messages)
│
├── signup\ # User signup/registration page
│
├── resources\ # Static resources (CSS, icons)
```


---

## 🛠️ Requirements

- MySQL Server (5.7+)
- XAMPP (Apache + MySQL)
- MySQL Workbench or equivalent
- PHP (for backend logic)
- (Optional) Basic HTML/CSS for frontend

---

## 🚀 Setup Instructions

1. Install and configure XAMPP (or any LAMP/WAMP stack).
2. Open MySQL Workbench or phpMyAdmin.
3. Import and run the database SQL file located in the `database/` folder.
4. Start Apache and MySQL from XAMPP control panel.
5. Place the project inside:  ```http://localhost/MESSAGING-SYSTEM/home```

---

## 🖥️ Access the App
Once setup is complete:

- Register as a new user.
- Login using your credentials.
- Start sending and editing messages!

---

## 💡 Core Features

### ✅ Database Design

- **Users Table**: Stores users' email, encrypted passwords (`password_hash`), usernames, and account creation time.
- **Messages Table**: Stores all user-to-user messages with sender/receiver details.
- **Log Table**: Tracks user actions like account creation, deletion, message sending, and editing.

### 🔐 Secure Login System

- Passwords are **encrypted and securely stored** in the database using `password_hash()` and verified with `password_verify()`.
- No plaintext passwords are ever saved or transmitted.

### ⚙️ Triggers

- Automatically log events like:
- New user registration
- Account deletion
- Sending and editing messages
- Auto-populate user IDs when sending messages using email lookups.

### 📑 Stored Procedures

- **FetchUserMessages**: Retrieve all messages for a specific user.
- **FetchUserActivity**: Fetch both sent/received messages and logged actions for a user.

### 👁️ Views

- **MessageView**: Combines user and message information to simplify queries and frontend displays.

### 🧪 Sample Data

- The database includes a few sample users and messages to quickly demonstrate functionality.

---

## 📌 Important Notes

- **Data Integrity**: Enforced through foreign key constraints and cascading deletes.
- **Auditability**: Every major action is logged automatically, making user activity fully traceable.
- **Security Best Practices**: Password encryption is implemented, and direct SQL queries are avoided where possible (prepared statements recommended on PHP side).
- **Scalability**: The design is modular, and new features like group chats or file uploads can be easily added.

---

## 🔒 Security Highlights

- Passwords are hashed before storing (using PHP's `password_hash()` and verified with `password_verify()`).
- No plain-text passwords are saved anywhere.
- Triggers ensure that all key user actions are logged automatically at the database level.

---

## ✨ Credits

Built by Hardik Jindal
Powered by PHP + MySQL + HTML/CSS

