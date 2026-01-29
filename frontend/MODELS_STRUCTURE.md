# Frontend Models Structure

## Directory Structure

```
src/app/core/models/
├── enums/
│   ├── user-role.enum.ts
│   ├── player-level.enum.ts
│   ├── player-status.enum.ts
│   └── attendance-status.enum.ts
├── interfaces/
│   ├── user.interface.ts
│   ├── auth.interface.ts
│   ├── player.interface.ts
│   ├── schedule.interface.ts
│   ├── attendance.interface.ts
│   ├── rating.interface.ts
│   ├── branch.interface.ts
│   └── monthly-record.interface.ts
└── index.ts (barrel export)
```

## Enums

### UserRole
- `Player`
- `Manager`
- `Admin`

### PlayerLevel
- `Beginner`
- `Intermediate`
- `Advanced`
- `Professional`

### PlayerStatus
- `Active`
- `Inactive`
- `Suspended`

### AttendanceStatus
- `Present`
- `Absent`
- `Late`
- `Excused`

## Interfaces

### User
- Basic user information
- Uses `UserRole` enum

### AuthResponse
- Authentication response with user and token

### Player
- Player information
- Uses `PlayerLevel` and `PlayerStatus` enums
- Related to User, Branch, Schedules, Ratings, Attendances

### Schedule
- Training schedule information
- Related to Player and Branch

### Attendance
- Attendance records
- Uses `AttendanceStatus` enum
- Related to Player and Schedule

### PlayerRating
- Player performance ratings
- Related to Player and User (ratedBy)

### Branch
- Branch/location information
- Related to Schedules and MonthlyRecords

### MonthlyRecord
- Monthly business records
- Related to Branch and User (creator)

## Filter Interfaces

- `PlayerFilters`
- `ScheduleFilters`
- `AttendanceFilters`
- `RatingFilters`
- `MonthlyRecordFilters`

## Usage

All models can be imported from the barrel export:

```typescript
import { User, Player, UserRole, PlayerLevel } from '../models';
```

Or from specific files:

```typescript
import { User } from '../models/interfaces/user.interface';
import { UserRole } from '../models/enums/user-role.enum';
```


