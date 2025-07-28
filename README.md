# School Management System

A comprehensive web-based school management system built with Laravel and modern web technologies. This system provides role-based access control for administrators, teachers, students, and parents with features for managing academic and administrative operations.

## 🚀 Features

### Core Functionality
- **Authentication & Authorization**: JWT-based authentication with role-based access control
- **User Management**: CRUD operations for students, teachers, parents, and administrators
- **Class & Subject Management**: Organize classes, sections, and subject assignments
- **Timetable Management**: Schedule and manage class timetables
- **Attendance Management**: Mark and track student attendance with detailed reporting
- **Exam & Result Management**: Create exams, input marks, and generate report cards
- **Notice Board**: School-wide announcements and notifications
- **Parent Portal**: Parent access to child's academic information
- **Role-based Dashboards**: Customized dashboards for each user type

### User Roles
- **Admin**: Full system access, user management, system configuration
- **Teacher**: Class management, attendance marking, grade input, announcements
- **Student**: View personal information, timetables, grades, attendance
- **Parent**: View children's academic progress, attendance, and school announcements

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: HTML/CSS with Tailwind CSS
- **Database**: MySQL
- **Authentication**: JWT (JSON Web Tokens)
- **API**: RESTful API architecture

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js & NPM (for frontend assets)

## ⚡ Quick Start

### 1. Installation

```bash
# Clone the repository
git clone <repository-url>
cd school-management-system

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env
```

### 2. Environment Configuration

Edit the `.env` file with your database and application settings:

```env
APP_NAME="School Management System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

JWT_SECRET=your_jwt_secret_key
```

### 3. Database Setup

```bash
# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 4. Start the Application

```bash
# Start the Laravel development server
php artisan serve

# In another terminal, build frontend assets
npm run dev
```

The application will be available at `http://localhost:8000`

## 👥 Demo Accounts

After seeding the database, you can use these demo accounts:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@school.com | admin123 |
| Teacher | john.smith@school.com | teacher123 |
| Student | alice.wilson@student.school.com | student123 |
| Parent | robert.wilson@parent.school.com | parent123 |

## 📚 API Documentation

### Authentication Endpoints

```http
POST /api/auth/login          # User login
POST /api/auth/register       # User registration
POST /api/auth/logout         # User logout
POST /api/auth/refresh        # Refresh JWT token
GET  /api/auth/me            # Get current user info
PUT  /api/auth/profile       # Update user profile
PUT  /api/auth/change-password # Change password
```

### Core Endpoints

```http
GET  /api/dashboard          # Role-based dashboard data
GET  /api/health            # API health check

# Student Management
GET    /api/students         # List students
POST   /api/students         # Create student
GET    /api/students/{id}    # Get student details
PUT    /api/students/{id}    # Update student
DELETE /api/students/{id}    # Delete student
GET    /api/students/profile # Student's own profile

# Attendance Management
GET  /api/attendance                    # Get attendance data
POST /api/attendance/mark              # Mark attendance
GET  /api/attendance/student/{id}/report # Student attendance report
GET  /api/attendance/class/{id}/report   # Class attendance report
GET  /api/attendance/statistics         # Attendance statistics
```

### Request/Response Examples

#### Login Request
```json
POST /api/auth/login
{
    "email": "admin@school.com",
    "password": "admin123"
}
```

#### Login Response
```json
{
    "success": true,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "name": "System Administrator",
        "email": "admin@school.com",
        "role": "admin"
    }
}
```

## 🗂️ Database Schema

### Core Tables

- **users**: Base user information for all roles
- **students**: Student-specific information
- **teachers**: Teacher-specific information  
- **parents**: Parent-specific information
- **classes**: School class/grade information
- **subjects**: Subject definitions
- **class_subject**: Subject assignments to classes
- **timetables**: Class scheduling
- **attendances**: Student attendance records
- **exams**: Examination definitions
- **exam_results**: Student exam results
- **announcements**: School announcements

### Key Relationships

- Users have roles (admin, teacher, student, parent)
- Students belong to classes
- Teachers can teach multiple subjects in multiple classes
- Parents can have multiple children (students)
- Classes have multiple subjects with assigned teachers
- Attendance is tracked per student per day
- Exams belong to specific classes and subjects

## 🔧 Configuration

### JWT Configuration

The JWT configuration is located in `config/jwt.php`. Key settings:

```php
'ttl' => env('JWT_TTL', 60),              // Token expiry (minutes)
'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Refresh token expiry
'algo' => env('JWT_ALGO', 'HS256'),       // Encryption algorithm
```

### Role Middleware

The system uses a custom role middleware for access control:

```php
// In routes
Route::middleware('role:admin,teacher')->group(function () {
    // Routes accessible by admin and teacher only
});
```

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/JWTAuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── StudentController.php
│   │   │   └── AttendanceController.php
│   │   └── Middleware/RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Student.php
│       ├── Teacher.php
│       ├── SchoolClass.php
│       ├── Subject.php
│       ├── Attendance.php
│       └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── resources/
    └── views/welcome.blade.php
```

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthenticationTest.php
```

## 🚀 Deployment

### Production Environment

1. **Server Requirements**:
   - PHP 8.1+
   - MySQL 5.7+
   - Composer
   - Web server (Apache/Nginx)

2. **Environment Configuration**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Optimization**:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Check the documentation in the `/docs` folder
- Review the API endpoints using the included Postman collection

## 🔮 Roadmap

### Phase 2 Features (Planned)
- [ ] Complete Teacher Management
- [ ] Exam and Results System
- [ ] Parent-Student Linking
- [ ] Timetable Management UI
- [ ] Fee Management
- [ ] Library Management
- [ ] Transport Management
- [ ] Report Generation (PDF)
- [ ] Mobile App API
- [ ] Real-time Notifications
- [ ] File Upload System

### Phase 3 Features (Future)
- [ ] Online Classes Integration
- [ ] Advanced Analytics
- [ ] Multi-school Support
- [ ] Plugin System
- [ ] Advanced Reporting
- [ ] Integration with Learning Management Systems

## 🏗️ Architecture

This system follows Laravel best practices:

- **MVC Pattern**: Clean separation of concerns
- **Repository Pattern**: For data access abstraction
- **Service Layer**: For business logic
- **JWT Authentication**: Stateless authentication
- **RESTful API**: Standard API design
- **Role-based Access Control**: Secure authorization
- **Database Migrations**: Version controlled database changes
- **Eloquent ORM**: For database interactions

## 📊 Performance Considerations

- **Database Indexing**: Proper indexes on frequently queried columns
- **Eager Loading**: Prevent N+1 query problems
- **Caching**: Configuration and route caching for production
- **API Rate Limiting**: Protect against abuse
- **Query Optimization**: Efficient database queries
- **Asset Optimization**: Minified CSS and JS for production

---

**Built with ❤️ using Laravel and modern web technologies**
