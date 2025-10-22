# 🏫 School Management System

A comprehensive Laravel-based School Management System with role-based access control, student management, teacher management, fee tracking, attendance, and more.

## ✨ Features

### 👨‍💼 Admin Features
- **Dashboard** with key statistics and analytics
- **Student Management** - Add, edit, view, and manage student records
- **Teacher Management** - Manage teaching staff and assignments
- **Class & Section Management** - Organize students into classes and sections
- **Fee Management** - Track payments, generate invoices, and manage fee structures
- **Attendance Tracking** - Monitor daily attendance for students and staff
- **Examination System** - Create exams, record grades, and generate report cards
- **Notice Board** - Post announcements and important notices
- **Timetable Management** - Create and manage class schedules

### 👨‍🏫 Teacher Features
- Personal dashboard with class information
- Student attendance marking
- Grade and exam management
- Assignment creation and tracking
- Class timetable access

### 👨‍🎓 Student Features
- Personal dashboard with academic information
- View grades and exam results
- Access assignments and homework
- Check attendance records
- View notices and announcements

### 👨‍👩‍👧‍👦 Parent Features
- Monitor child's academic progress
- View attendance and grades
- Receive school notifications
- Fee payment status tracking

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (default) or MySQL

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd School_management_system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   
   # Run migrations and seed data
   php artisan migrate:fresh --seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

## 🔐 Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@school.com | password |
| **Teacher** | teacher@school.com | password |
| **Student** | student@school.com | password |
| **Parent** | parent@school.com | password |

## 🏗️ Architecture

### Database Schema
- **Users** - Base user authentication with role-based access
- **Roles** - Admin, Teacher, Student, Parent roles
- **Students** - Student profiles with academic information
- **Teachers** - Teacher profiles and qualifications
- **Classes & Sections** - Academic organization structure
- **Subjects** - Course management
- **Fees** - Financial tracking and payment management
- **Attendance** - Daily attendance records
- **Exams & Grades** - Assessment and grading system
- **Assignments** - Homework and project management
- **Notices** - Communication and announcements
- **Timetables** - Class scheduling

### Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS 4.0
- **Database**: SQLite (default) / MySQL
- **Build Tool**: Vite
- **Authentication**: Laravel Auth with role-based middleware

## 📱 Responsive Design

The application is fully responsive and works seamlessly on:
- Desktop computers
- Tablets
- Mobile phones

## 🔒 Security Features

- CSRF protection
- XSS prevention
- Role-based access control
- Secure password hashing
- Input validation and sanitization
- SQL injection prevention

## 🚀 Development

### Running in Development Mode
```bash
# Start Laravel server
php artisan serve

# Start Vite dev server (in another terminal)
npm run dev
```

### Database Management
```bash
# Reset database with fresh data
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new model with migration
php artisan make:model ModelName -m
```

### Creating New Features
```bash
# Create controller
php artisan make:controller ControllerName

# Create middleware
php artisan make:middleware MiddlewareName

# Create request validation
php artisan make:request RequestName
```

## 📊 Key Statistics Dashboard

The admin dashboard provides real-time insights:
- Total students, teachers, and classes
- Pending and overdue fee amounts
- Daily attendance statistics
- Recent student registrations
- Upcoming fee due dates

## 🎯 Future Enhancements

- **Mobile App** - React Native or Flutter mobile application
- **SMS Integration** - Automated SMS notifications for parents
- **Online Payments** - Payment gateway integration
- **Library Management** - Book tracking and lending system
- **Transport Management** - Bus route and student transport tracking
- **Hostel Management** - Dormitory and accommodation management
- **Multi-language Support** - Internationalization
- **Advanced Reporting** - PDF reports and analytics
- **API Development** - RESTful API for third-party integrations

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For support and questions, please create an issue in the repository.

---

**Built with ❤️ using Laravel & Tailwind CSS**