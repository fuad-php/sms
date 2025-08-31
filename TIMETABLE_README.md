# Timetable Management System

## Overview
The Timetable Management System provides comprehensive functionality for managing school class schedules, including time slots, room assignments, and teacher schedules.

## Features

### 1. **Timetable Management**
- **Create**: Add new timetable entries with class, subject, teacher, day, time, and room
- **Read**: View individual timetable details and list all entries
- **Update**: Edit existing timetable entries
- **Delete**: Remove timetable entries
- **Filter**: Filter by class, teacher, day, and status

### 2. **Conflict Detection**
- **Class Conflicts**: Prevents double-booking of classes during the same time slot
- **Teacher Conflicts**: Prevents teachers from being assigned to multiple classes simultaneously
- **Time Validation**: Ensures end time is after start time

### 3. **Views**
- **List View**: Tabular display of all timetable entries with filtering
- **Weekly Overview**: Grid-based weekly schedule display
- **Individual View**: Detailed view of specific timetable entries

### 4. **Data Structure**
- **Class Assignment**: Links to SchoolClass model
- **Subject Assignment**: Links to Subject model  
- **Teacher Assignment**: Links to User model (teachers only)
- **Time Management**: Start and end times with duration calculation
- **Room Assignment**: Optional room specification
- **Status Management**: Active/inactive status

## Database Schema

```sql
timetables
├── id (Primary Key)
├── class_id (Foreign Key to classes)
├── subject_id (Foreign Key to subjects)
├── teacher_id (Foreign Key to users)
├── day_of_week (enum: monday, tuesday, wednesday, thursday, friday, saturday)
├── start_time (time)
├── end_time (time)
├── room (string, nullable)
├── is_active (boolean)
├── created_at (timestamp)
└── updated_at (timestamp)
```

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/timetable` | `timetable.index` | List all timetables |
| GET | `/timetable/weekly` | `timetable.weekly` | Weekly overview |
| GET | `/timetable/create` | `timetable.create` | Create form |
| POST | `/timetable` | `timetable.store` | Store new timetable |
| GET | `/timetable/{id}` | `timetable.show` | Show timetable details |
| GET | `/timetable/{id}/edit` | `timetable.edit` | Edit form |
| PUT | `/timetable/{id}` | `timetable.update` | Update timetable |
| DELETE | `/timetable/{id}` | `timetable.destroy` | Delete timetable |

## Models

### Timetable Model
- **Relationships**: `class()`, `subject()`, `teacher()`
- **Scopes**: `active()`, `forDay()`, `forClass()`, `forTeacher()`
- **Helper Methods**: 
  - `getDurationInMinutes()`
  - `getDurationFormatted()`
  - `getTimeRange()`
  - `isCurrentPeriod()`
  - `isUpcoming()`

## Controllers

### TimetableController
- **CRUD Operations**: Full create, read, update, delete functionality
- **Conflict Detection**: Built-in scheduling conflict prevention
- **Filtering**: Support for filtering by various criteria
- **Validation**: Comprehensive input validation

## Views

### 1. **Index View** (`timetable/index.blade.php`)
- Filterable list of all timetable entries
- Quick actions (add, weekly view)
- Pagination support
- Status indicators

### 2. **Create View** (`timetable/create.blade.php`)
- Form for adding new timetable entries
- Dropdown selections for class, subject, teacher
- Time input fields
- Room assignment
- Active status toggle

### 3. **Edit View** (`timetable/edit.blade.php`)
- Pre-populated form for editing entries
- Same fields as create view
- Update functionality

### 4. **Show View** (`timetable/show.blade.php`)
- Detailed view of timetable entry
- Current status indicators
- Action buttons (edit, delete)

### 5. **Weekly Overview** (`timetable/weekly-overview.blade.php`)
- Grid-based weekly schedule
- Class filtering
- Time slot visualization
- Legend and navigation

## Usage Examples

### Creating a Timetable Entry
```php
$timetable = Timetable::create([
    'class_id' => 1,
    'subject_id' => 2,
    'teacher_id' => 3,
    'day_of_week' => 'monday',
    'start_time' => '08:00',
    'end_time' => '08:45',
    'room' => 'Room 101',
    'is_active' => true,
]);
```

### Filtering Timetables
```php
// Get timetables for a specific class
$classTimetables = Timetable::forClass(1)->get();

// Get timetables for a specific day
$mondayTimetables = Timetable::forDay('monday')->get();

// Get active timetables
$activeTimetables = Timetable::active()->get();
```

### Checking Current Status
```php
$timetable = Timetable::find(1);

if ($timetable->isCurrentPeriod()) {
    echo "This period is currently active";
} elseif ($timetable->isUpcoming()) {
    echo "This period is coming up next";
}
```

## Security & Permissions

- **Create/Edit/Delete**: Restricted to admin and teacher roles
- **View**: Available to all authenticated users
- **Conflict Prevention**: Server-side validation prevents scheduling conflicts

## Localization

The system supports multiple languages:
- **English**: Primary language
- **Bengali**: Secondary language
- **Translation Keys**: All user-facing text uses `__('app.key')` syntax

## Future Enhancements

1. **Bulk Import/Export**: CSV import/export functionality
2. **Advanced Conflict Resolution**: Suggestions for resolving conflicts
3. **Recurring Schedules**: Support for weekly recurring patterns
4. **Calendar Integration**: Export to calendar applications
5. **Notifications**: Reminders for upcoming classes
6. **Analytics**: Usage statistics and reports

## Testing

To test the system:

1. **Seed Data**: Run `php artisan db:seed --class=TimetableSeeder`
2. **Create Entries**: Use the create form to add sample timetables
3. **Test Conflicts**: Try to create overlapping schedules
4. **View Weekly**: Check the weekly overview for visual representation

## Dependencies

- **Laravel Framework**: Core framework
- **Tailwind CSS**: Styling
- **Heroicons**: Icon system
- **Carbon**: Date/time manipulation
- **Eloquent ORM**: Database operations

## Support

For issues or questions regarding the Timetable Management System, please refer to the main project documentation or contact the development team.
