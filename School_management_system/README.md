# School Management System

A comprehensive Laravel-based school management system with role-based access control for administrators, teachers, students, and parents.

## Features

### Admin Features
- **Dashboard**: Overview of school statistics and recent activities
- **Student Management**: Add, edit, view, and manage student records
- **Teacher Management**: Manage teacher profiles and assignments
- **Class & Subject Management**: Organize classes, sections, and subjects
- **Attendance Management**: Mark and track student attendance
- **Exam & Grade Management**: Create exams and manage student grades
- **Timetable Management**: Create and manage class schedules
- **Fee Management**: Track student fees and payments
- **Notice Management**: Create and publish school notices
- **Assignment Management**: Manage homework and assignments

### Teacher Features
- **Dashboard**: View today's classes, upcoming exams, and notices
- **Class Overview**: See assigned classes and students
- **Attendance Tracking**: Mark student attendance
- **Grade Management**: Enter and manage student grades
- **Assignment Creation**: Create and manage assignments

### Student Features
- **Dashboard**: View personal academic information
- **Timetable**: See daily class schedule
- **Grades**: View exam results and grades
- **Attendance**: Check attendance records
- **Assignments**: View pending assignments
- **Notices**: Read school announcements

### Parent Features
- **Dashboard**: Monitor children's academic progress
- **Attendance Monitoring**: Track children's attendance
- **Grade Tracking**: View children's exam results
- **Fee Status**: Check pending fee payments
- **School Communication**: Receive school notices and updates

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd School_management_system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate --seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## Demo Credentials

The system comes with pre-configured demo accounts:

- **Administrator**: admin@school.com / password
- **Teacher**: teacher@school.com / password  
- **Student**: student@school.com / password
- **Parent**: parent@school.com / password

## System Architecture

### Models & Relationships
- **User**: Base user model with role-based authentication
- **Role**: Defines user roles (admin, teacher, student, parent)
- **Student**: Student profiles linked to users
- **Teacher**: Teacher profiles with qualifications and assignments
- **SchoolClass**: Class/grade definitions
- **Section**: Class sections (A, B, C, etc.)
- **Subject**: Academic subjects with teacher assignments
- **Attendance**: Daily attendance records
- **Exam**: Exam definitions and scheduling
- **Grade**: Student exam results
- **Fee**: Student fee management
- **Notice**: School announcements and notices
- **Assignment**: Homework and assignment management
- **Timetable**: Class scheduling system

### Key Features
- **Role-based Access Control**: Different interfaces for each user type
- **Responsive Design**: Works on desktop and mobile devices
- **Real-time Data**: Dynamic dashboards with live statistics
- **Comprehensive Reporting**: Attendance reports, grade summaries
- **Multi-class Support**: Handle multiple classes and sections
- **Academic Year Management**: Support for different academic years

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **Authentication**: Laravel's built-in authentication
- **UI Components**: Alpine.js for interactive elements

## File Structure

```
School_management_system/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Teacher/        # Teacher controllers
│   │   ├── Student/        # Student controllers
│   │   └── Parent/         # Parent controllers
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   └── views/
│       ├── admin/         # Admin views
│       ├── teacher/       # Teacher views
│       ├── student/       # Student views
│       ├── parent/        # Parent views
│       ├── auth/          # Authentication views
│       └── layouts/       # Layout templates
└── routes/
    └── web.php            # Application routes
```

## Usage

1. **Login** with one of the demo accounts
2. **Navigate** using the role-specific menu system
3. **Manage** students, teachers, classes as an admin
4. **Track** attendance and grades as a teacher
5. **Monitor** progress as a student or parent

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support and questions, please create an issue in the repository.