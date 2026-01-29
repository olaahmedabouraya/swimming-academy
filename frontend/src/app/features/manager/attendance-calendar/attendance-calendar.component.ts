import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { AttendanceService } from '../../../core/services/attendance.service';
import { TrainingSessionService } from '../../../core/services/training-session.service';
import { BranchService } from '../../../core/services/branch.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';
import { CalendarComponent } from '../../../core/components/calendar/calendar.component';
import { TrainingSession } from '../../../core/models';

@Component({
  selector: 'app-attendance-calendar',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent, CalendarComponent],
  templateUrl: './attendance-calendar.component.html',
  styleUrl: './attendance-calendar.component.scss'
})
export class AttendanceCalendarComponent implements OnInit {
  selectedDate = signal(new Date().toISOString().split('T')[0]);
  calendarSelectedDate = signal<Date | null>(new Date());
  sessions = signal<TrainingSession[]>([]);
  selectedSession = signal<TrainingSession | null>(null);
  players = signal<any[]>([]);
  branches = signal<any[]>([]);
  filterBranchId = signal<number | null>(null);
  loading = signal(true);
  saving = signal(false);
  viewMode = signal<'calendar' | 'list'>('calendar');
  
  attendanceData = signal<Map<number, { status: string; check_in_time?: string; check_out_time?: string; notes?: string }>>(new Map());
  window = window;

  calendarEvents = computed(() => {
    const events: Array<{ date: Date; color?: string; title?: string }> = [];
    // Load attendance data for calendar events
    this.sessions().forEach(session => {
      const dayMap: { [key: string]: number } = {
        'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3,
        'Thursday': 4, 'Friday': 5, 'Saturday': 6
      };
      
      const targetDay = dayMap[session.day_of_week];
      const today = new Date();
      const startDate = new Date(today.getFullYear(), today.getMonth(), 1);
      const endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
      
      let currentDate = new Date(startDate);
      while (currentDate <= endDate) {
        if (currentDate.getDay() === targetDay) {
          events.push({
            date: new Date(currentDate),
            color: '#51cf66',
            title: `${session.start_time} - ${session.end_time}`
          });
        }
        currentDate.setDate(currentDate.getDate() + 1);
      }
    });
    return events;
  });

  constructor(
    public authService: AuthService,
    private attendanceService: AttendanceService,
    private sessionService: TrainingSessionService,
    private branchService: BranchService
  ) {}

  ngOnInit() {
    this.loadBranches();
    // Set to current date/time and load current session
    const now = new Date();
    this.selectedDate.set(now.toISOString().split('T')[0]);
    this.calendarSelectedDate.set(now);
    this.loadSessionsForDate();
  }

  loadBranches() {
    this.branchService.getAllBranches().subscribe({
      next: (data: any) => {
        this.branches.set(data);
      },
      error: (err) => {
        console.error('Error loading branches:', err);
      }
    });
  }

  loadSessionsForDate() {
    this.loading.set(true);
    const branchId = this.filterBranchId();
    const selectedDate = this.selectedDate();
    
    this.attendanceService.getSessionsForDate(selectedDate, branchId || undefined).subscribe({
      next: (data: any) => {
        this.sessions.set(data);
        
        // Auto-select current session based on current time
        if (data.length > 0 && !this.selectedSession()) {
          const now = new Date();
          const currentDay = now.toLocaleDateString('en-US', { weekday: 'long' });
          const currentTime = now.toTimeString().slice(0, 5); // HH:MM format
          
          // Find session matching current day and time
          const currentSession = data.find((s: any) => {
            if (s.day_of_week !== currentDay) return false;
            const sessionStart = s.start_time;
            const sessionEnd = s.end_time;
            return currentTime >= sessionStart && currentTime <= sessionEnd;
          });
          
          if (currentSession) {
            this.selectSession(currentSession);
          } else {
            // If no current session, select first one
            this.selectSession(data[0]);
          }
        }
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading sessions:', err);
        this.loading.set(false);
      }
    });
  }

  onDateChange() {
    this.loadSessionsForDate();
    this.selectedSession.set(null);
    this.players.set([]);
    this.attendanceData.set(new Map());
  }

  onCalendarDateSelected(date: Date) {
    this.calendarSelectedDate.set(date);
    this.selectedDate.set(date.toISOString().split('T')[0]);
    this.onDateChange();
  }

  toggleViewMode() {
    this.viewMode.set(this.viewMode() === 'list' ? 'calendar' : 'list');
  }

  onBranchFilterChange() {
    this.loadSessionsForDate();
  }

  selectSession(session: TrainingSession) {
    this.selectedSession.set(session);
    this.loadPlayersForSession(session.id!);
  }

  loadPlayersForSession(sessionId: number) {
    this.attendanceService.getPlayersForSession(sessionId, this.selectedDate()).subscribe({
      next: (data: any) => {
        this.players.set(data);
        // Initialize attendance data
        const attendanceMap = new Map();
        data.forEach((player: any) => {
          attendanceMap.set(player.id, {
            status: 'present',
            check_in_time: '',
            check_out_time: '',
            notes: ''
          });
        });
        this.attendanceData.set(attendanceMap);
      },
      error: (err) => {
        console.error('Error loading players:', err);
      }
    });
  }

  updateAttendanceStatus(playerId: number, status: string) {
    const current = this.attendanceData();
    const playerData = current.get(playerId) || { status: 'present' };
    playerData.status = status;
    current.set(playerId, playerData);
    this.attendanceData.set(new Map(current));
  }

  updateAttendanceTime(playerId: number, field: 'check_in_time' | 'check_out_time', value: string) {
    const current = this.attendanceData();
    const playerData = current.get(playerId) || { status: 'present' };
    playerData[field] = value;
    current.set(playerId, playerData);
    this.attendanceData.set(new Map(current));
  }

  updateAttendanceNotes(playerId: number, notes: string) {
    const current = this.attendanceData();
    const playerData = current.get(playerId) || { status: 'present' };
    playerData.notes = notes;
    current.set(playerId, playerData);
    this.attendanceData.set(new Map(current));
  }

  saveAttendances() {
    if (!this.selectedSession()) {
      window.alert('Please select a session');
      return;
    }

    this.saving.set(true);
    const attendances = Array.from(this.attendanceData().entries()).map(([playerId, data]) => ({
      player_id: playerId,
      session_id: this.selectedSession()!.id,
      attendance_date: this.selectedDate(),
      status: data.status as 'present' | 'absent' | 'late' | 'excused',
      check_in_time: data.check_in_time || undefined,
      check_out_time: data.check_out_time || undefined,
      notes: data.notes || undefined
    }));

    this.attendanceService.markMultiple(attendances).subscribe({
      next: () => {
        this.saving.set(false);
        window.alert('Attendance marked successfully!');
        this.loadPlayersForSession(this.selectedSession()!.id!);
      },
      error: (err) => {
        console.error('Error marking attendance:', err);
        window.alert('Failed to mark attendance. Please try again.');
        this.saving.set(false);
      }
    });
  }
}
