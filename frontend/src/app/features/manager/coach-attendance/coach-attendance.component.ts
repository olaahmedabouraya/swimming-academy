import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { CoachAttendanceService } from '../../../core/services/coach-attendance.service';
import { CoachService } from '../../../core/services/coach.service';
import { TrainingSessionService } from '../../../core/services/training-session.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';
import { CalendarComponent } from '../../../core/components/calendar/calendar.component';
import { CoachAttendance } from '../../../core/models';

@Component({
  selector: 'app-coach-attendance',
  standalone: true,
  imports: [CommonModule, RouterLink, FormsModule, LogoComponent, CalendarComponent],
  templateUrl: './coach-attendance.component.html',
  styleUrl: './coach-attendance.component.scss'
})
export class CoachAttendanceComponent implements OnInit {
  attendances = signal<CoachAttendance[]>([]);
  coaches = signal<any[]>([]);
  sessions = signal<any[]>([]);
  loading = signal(true);
  saving = signal(false);
  selectedDate = signal(new Date().toISOString().split('T')[0]);
  calendarSelectedDate = signal<Date | null>(new Date());
  filterCoachId = signal<number | null>(null);
  filterSessionId = signal<number | null>(null);
  
  showAddModal = signal(false);
  newAttendance = signal({
    coach_id: null as number | null,
    session_id: null as number | null,
    attendance_date: new Date().toISOString().split('T')[0],
    scheduled_start_time: '',
    scheduled_end_time: '',
    actual_start_time: '',
    actual_end_time: '',
    is_late: false,
    late_minutes: 0,
    notes: '',
    hours_worked: 0
  });
  
  window = window;

  constructor(
    public authService: AuthService,
    private attendanceService: CoachAttendanceService,
    private coachService: CoachService,
    private sessionService: TrainingSessionService
  ) {}

  ngOnInit() {
    this.loadCoaches();
    this.loadSessions();
    this.loadAttendances();
  }

  loadCoaches() {
    this.coachService.getAllCoaches().subscribe({
      next: (data: any) => {
        this.coaches.set(data);
      },
      error: (err) => {
        console.error('Error loading coaches:', err);
      }
    });
  }

  loadSessions() {
    this.sessionService.getAllSessions({}).subscribe({
      next: (data: any) => {
        this.sessions.set(data);
      },
      error: (err) => {
        console.error('Error loading sessions:', err);
      }
    });
  }

  loadAttendances() {
    this.loading.set(true);
    const filters: any = {
      date: this.selectedDate()
    };
    if (this.filterCoachId()) filters.coach_id = this.filterCoachId();
    if (this.filterSessionId()) filters.session_id = this.filterSessionId();

    this.attendanceService.getAllAttendances(filters).subscribe({
      next: (data: any) => {
        this.attendances.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading attendances:', err);
        this.loading.set(false);
      }
    });
  }

  onDateChange() {
    this.loadAttendances();
  }

  onCalendarDateSelected(date: Date) {
    this.calendarSelectedDate.set(date);
    this.selectedDate.set(date.toISOString().split('T')[0]);
    this.newAttendance().attendance_date = date.toISOString().split('T')[0];
    this.onDateChange();
  }

  onFilterChange() {
    this.loadAttendances();
  }

  openAddModal() {
    const today = new Date().toISOString().split('T')[0];
    this.newAttendance.set({
      coach_id: null,
      session_id: null,
      attendance_date: today,
      scheduled_start_time: '',
      scheduled_end_time: '',
      actual_start_time: '',
      actual_end_time: '',
      is_late: false,
      late_minutes: 0,
      notes: '',
      hours_worked: 0
    });
    this.showAddModal.set(true);
  }

  closeAddModal() {
    this.showAddModal.set(false);
  }

  onSessionSelected() {
    const sessionId = this.newAttendance().session_id;
    const session = this.sessions().find(s => s.id === sessionId);
    if (session) {
      this.newAttendance().scheduled_start_time = session.start_time;
      this.newAttendance().scheduled_end_time = session.end_time;
      this.calculateLateStatus();
    }
  }

  calculateLateStatus() {
    const scheduled = this.newAttendance().scheduled_start_time;
    const actual = this.newAttendance().actual_start_time;
    
    if (scheduled && actual) {
      const scheduledTime = new Date(`2000-01-01T${scheduled}`);
      const actualTime = new Date(`2000-01-01T${actual}`);
      
      if (actualTime > scheduledTime) {
        const diffMinutes = (actualTime.getTime() - scheduledTime.getTime()) / (1000 * 60);
        this.newAttendance().is_late = true;
        this.newAttendance().late_minutes = Math.round(diffMinutes);
      } else {
        this.newAttendance().is_late = false;
        this.newAttendance().late_minutes = 0;
      }
      
      this.calculateHoursWorked();
    }
  }

  calculateHoursWorked() {
    const start = this.newAttendance().actual_start_time;
    const end = this.newAttendance().actual_end_time;
    
    if (start && end) {
      const startTime = new Date(`2000-01-01T${start}`);
      const endTime = new Date(`2000-01-01T${end}`);
      const diffHours = (endTime.getTime() - startTime.getTime()) / (1000 * 60 * 60);
      this.newAttendance().hours_worked = Math.round(diffHours * 100) / 100;
    }
  }

  saveAttendance() {
    if (!this.newAttendance().coach_id || !this.newAttendance().session_id) {
      window.alert('Please fill in all required fields');
      return;
    }

    this.saving.set(true);
    this.attendanceService.createAttendance(this.newAttendance()).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadAttendances();
      },
      error: (err) => {
        console.error('Error creating attendance:', err);
        window.alert('Failed to create attendance. Please try again.');
        this.saving.set(false);
      }
    });
  }

  deleteAttendance(id: number) {
    if (window.confirm('Are you sure you want to delete this attendance record?')) {
      this.attendanceService.deleteAttendance(id).subscribe({
        next: () => {
          this.loadAttendances();
        },
        error: (err) => {
          console.error('Error deleting attendance:', err);
          window.alert('Failed to delete attendance.');
        }
      });
    }
  }
}
