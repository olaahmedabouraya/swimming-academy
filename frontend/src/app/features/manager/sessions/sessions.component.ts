import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { TrainingSessionService } from '../../../core/services/training-session.service';
import { BranchService } from '../../../core/services/branch.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';
import { CalendarComponent } from '../../../core/components/calendar/calendar.component';
import { TrainingSession } from '../../../core/models';

@Component({
  selector: 'app-sessions',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent, CalendarComponent],
  templateUrl: './sessions.component.html',
  styleUrl: './sessions.component.scss'
})
export class SessionsComponent implements OnInit {
  sessions = signal<TrainingSession[]>([]);
  branches = signal<any[]>([]);
  loading = signal(true);
  showAddModal = signal(false);
  selectedDate = signal(new Date().toISOString().split('T')[0]);
  filterBranchId = signal<number | null>(null);
  filterGroup = signal<number | null>(null);
  viewMode = signal<'list' | 'calendar'>('calendar');
  
  // Getters and setters for filterGroup to work with ngModel
  get filterGroupValue(): number | null {
    return this.filterGroup();
  }
  
  set filterGroupValue(value: number | null | string) {
    if (value === null || value === undefined || value === 'null' || value === '') {
      this.filterGroup.set(null);
    } else {
      const numValue = typeof value === 'string' ? parseInt(value, 10) : Number(value);
      this.filterGroup.set(isNaN(numValue) ? null : numValue);
    }
  }

  // Getters and setters for group in newSession to work with ngModel
  get newSessionGroup(): number | null {
    return this.newSession().group;
  }
  
  set newSessionGroup(value: number | null | string) {
    const normalized = this.normalizeGroupValue(value);
    this.newSession.update(session => ({
      ...session,
      group: normalized
    }));
  }
  calendarSelectedDate = signal<Date | null>(new Date());
  
  calendarEvents = computed(() => {
    return this.sessions().map(session => {
      // Create events for each occurrence of the session
      const events: Array<{ date: Date; color?: string; title?: string }> = [];
      const startDate = session.start_date ? new Date(session.start_date) : new Date();
      const endDate = session.end_date ? new Date(session.end_date) : new Date(new Date().getFullYear() + 1, 11, 31);
      
      const dayMap: { [key: string]: number } = {
        'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3,
        'Thursday': 4, 'Friday': 5, 'Saturday': 6
      };
      
      const targetDay = dayMap[session.day_of_week];
      let currentDate = new Date(startDate);
      
      while (currentDate <= endDate) {
        if (currentDate.getDay() === targetDay) {
          events.push({
            date: new Date(currentDate),
            color: '#51cf66',
            title: `${this.formatTime(session.start_time)} - ${this.formatTime(session.end_time)}`
          });
        }
        currentDate.setDate(currentDate.getDate() + 1);
      }
      
      return events;
    }).flat();
  });
  
  newSession = signal({
    branch_id: null as number | null,
    group: null as number | null,
    day_of_week: (() => {
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      return days[new Date().getDay()];
    })(),
    start_time: '16:00',
    end_time: '17:00',
    start_date: new Date().toISOString().split('T')[0],
    end_date: null as string | null,
    is_active: true,
    max_capacity: 20,
    notes: ''
  });

  editingSession = signal<TrainingSession | null>(null);
  showEditModal = signal(false);
  
  saving = signal(false);
  daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  window = window;

  private dayMap: { [key: string]: number } = {
    'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3,
    'Thursday': 4, 'Friday': 5, 'Saturday': 6
  };

  private getDayOfWeekFromDate(date: Date): string {
    return this.daysOfWeek[date.getDay()];
  }

  /**
   * Updates the day of week to match the selected start date
   */
  onStartDateChange() {
    const startDate = this.newSession().start_date;
    
    if (!startDate) return;

    const date = new Date(startDate);
    const dayName = this.getDayOfWeekFromDate(date);
    
    this.newSession.update(session => ({
      ...session,
      day_of_week: dayName
    }));
  }

  constructor(
    public authService: AuthService,
    private sessionService: TrainingSessionService,
    private branchService: BranchService
  ) {}

  ngOnInit() {
    this.loadBranches();
    this.loadSessions();
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

  loadSessions() {
    this.loading.set(true);
    const filters: any = {};
    if (this.filterBranchId()) {
      filters.branch_id = this.filterBranchId();
    }
    if (this.filterGroup() !== null) {
      filters.group = this.filterGroup();
    }
    // Don't filter by date - we need all sessions to show them on all matching days
    // The frontend will filter by date when displaying in calendar view

    this.sessionService.getAllSessions(filters).subscribe({
      next: (data: any) => {
        this.sessions.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading sessions:', err);
        this.loading.set(false);
      }
    });
  }

  onDateChange() {
    // Update the calendar selected date to match the input date
    if (this.selectedDate()) {
      // Parse date string in local timezone to avoid timezone shifts
      const dateParts = this.selectedDate().split('-');
      const year = parseInt(dateParts[0], 10);
      const month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed
      const day = parseInt(dateParts[2], 10);
      const localDate = new Date(year, month, day);
      this.calendarSelectedDate.set(localDate);
    }
    // Don't reload sessions - filtering is done client-side
  }

  onBranchFilterChange() {
    this.loadSessions();
  }

  onGroupFilterChange() {
    this.loadSessions();
  }

  /**
   * Normalizes group value to number or null
   */
  private normalizeGroupValue(value: any): number | null {
    if (value === null || value === undefined || value === '') {
      return null;
    }
    const numValue = typeof value === 'string' ? parseInt(value, 10) : Number(value);
    return isNaN(numValue) || numValue < 1 ? null : numValue;
  }


  getUniqueGroups(): number[] {
    const groups = this.sessions()
      .map(s => s.group)
      .filter((g): g is number => g !== null && g !== undefined && typeof g === 'number' && g >= 1)
      .filter((g, i, arr) => arr.indexOf(g) === i)
      .sort((a, b) => a - b);
    return groups;
  }

  openAddModal() {
    // Set initial values - day of week will be set based on the start date
    const today = new Date();
    const todayDateString = today.toISOString().split('T')[0];
    
    this.newSession.update(session => ({
      ...session,
      start_date: todayDateString,
      day_of_week: this.daysOfWeek[today.getDay()]
    }));
    
    this.showAddModal.set(true);
  }

  closeAddModal() {
    this.showAddModal.set(false);
    // Reset to default values
    const today = new Date();
    const todayDateString = today.toISOString().split('T')[0];
    
    this.newSession.set({
      branch_id: null,
      group: null,
      day_of_week: this.getDayOfWeekFromDate(today),
      start_time: '16:00',
      end_time: '17:00',
      start_date: todayDateString,
      end_date: null,
      is_active: true,
      max_capacity: 20,
      notes: ''
    });
  }

  openEditModal(session: TrainingSession) {
    this.editingSession.set(session);
    const startDate = session.start_date || new Date().toISOString().split('T')[0];
    const date = new Date(startDate);
    
    // Ensure group is properly set (could be number or null, but not undefined)
    // Handle both number and string representations (in case backend returns string)
    let groupValue: number | null = null;
    const rawGroup = (session as any).group; // Use any to handle potential string from backend
    if (rawGroup !== null && rawGroup !== undefined) {
      if (typeof rawGroup === 'string' && rawGroup !== '') {
        const parsed = parseInt(rawGroup, 10);
        groupValue = isNaN(parsed) || parsed < 1 ? null : parsed;
      } else if (typeof rawGroup === 'number') {
        groupValue = rawGroup >= 1 ? rawGroup : null;
      }
    }
    
    this.newSession.set({
      branch_id: session.branch_id || null,
      group: groupValue,
      day_of_week: this.getDayOfWeekFromDate(date),
      start_time: session.start_time,
      end_time: session.end_time,
      start_date: startDate,
      end_date: session.end_date || null,
      is_active: session.is_active,
      max_capacity: session.max_capacity || 20,
      notes: session.notes || ''
    });
    this.showEditModal.set(true);
  }

  closeEditModal() {
    this.showEditModal.set(false);
    this.editingSession.set(null);
    this.closeAddModal();
  }

  updateSession() {
    const session = this.editingSession();
    if (!session || !session.id) return;

    this.saving.set(true);
    // Normalize group value - convert to number or null
    const rawGroup = this.newSession().group;
    const groupValue = this.normalizeGroupValue(rawGroup);
    
    // Build sessionData object - ensure group is always included
    const sessionData: any = {
      branch_id: this.newSession().branch_id,
      day_of_week: this.newSession().day_of_week,
      start_time: this.newSession().start_time,
      end_time: this.newSession().end_time,
      start_date: this.newSession().start_date || undefined,
      end_date: this.newSession().end_date || undefined,
      max_capacity: this.newSession().max_capacity,
      notes: this.newSession().notes || undefined,
      is_active: this.newSession().is_active
    };
    
    // Always include group field explicitly (even if null)
    // Some backends require the field to be present to update it
    if (groupValue !== null && groupValue !== undefined) {
      sessionData.group = groupValue;
    } else {
      sessionData.group = null;
    }
    
    this.sessionService.updateSession(session.id, sessionData).subscribe({
      next: (updatedSession) => {
        this.saving.set(false);
        this.closeEditModal();
        this.loadSessions();
      },
      error: (err) => {
        console.error('Error updating session:', err);
        console.error('Error details:', JSON.stringify(err, null, 2));
        window.alert('Failed to update session. Please try again.');
        this.saving.set(false);
      }
    });
  }

  saveSession() {
    if (!this.newSession().branch_id) {
      window.alert('Please select a branch');
      return;
    }

    this.saving.set(true);
    // Normalize group value - convert to number or null
    const groupValue = this.normalizeGroupValue(this.newSession().group);
    
    const sessionData: any = {
      branch_id: this.newSession().branch_id,
      day_of_week: this.newSession().day_of_week,
      start_time: this.newSession().start_time,
      end_time: this.newSession().end_time,
      start_date: this.newSession().start_date || undefined,
      end_date: this.newSession().end_date || undefined,
      max_capacity: this.newSession().max_capacity,
      notes: this.newSession().notes || undefined,
      is_active: this.newSession().is_active
    };
    
    // Always include group field explicitly (even if null)
    if (groupValue !== null && groupValue !== undefined) {
      sessionData.group = groupValue;
    } else {
      sessionData.group = null;
    }
    
    this.sessionService.createSession(sessionData).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadSessions();
      },
      error: (err) => {
        console.error('Error creating session:', err);
        window.alert('Failed to create session. Please try again.');
        this.saving.set(false);
      }
    });
  }

  deleteSession(id: number) {
    if (window.confirm('Are you sure you want to delete this session?')) {
      this.sessionService.deleteSession(id).subscribe({
        next: () => {
          this.loadSessions();
        },
        error: (err) => {
          console.error('Error deleting session:', err);
          window.alert('Failed to delete session.');
        }
      });
    }
  }


  onCalendarDateSelected(date: Date) {
    // Create a new date object to avoid timezone issues
    const localDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    this.calendarSelectedDate.set(localDate);
    
    // Format date as YYYY-MM-DD in local timezone
    const year = localDate.getFullYear();
    const month = String(localDate.getMonth() + 1).padStart(2, '0');
    const day = String(localDate.getDate()).padStart(2, '0');
    this.selectedDate.set(`${year}-${month}-${day}`);
    
    // Don't reload sessions - we already have all sessions loaded
    // The getSessionsForDate function will filter them client-side
  }

  toggleViewMode() {
    this.viewMode.set(this.viewMode() === 'list' ? 'calendar' : 'list');
  }

  /**
   * Converts 24-hour time format (HH:mm) to 12-hour format with AM/PM
   */
  formatTime(time24: string): string {
    if (!time24) return '';
    const [hours, minutes] = time24.split(':');
    const hour = parseInt(hours, 10);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${hour12}:${minutes} ${ampm}`;
  }

  getSessionsForDate(date: Date): TrainingSession[] {
    const dayMap: { [key: string]: number } = {
      'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3,
      'Thursday': 4, 'Friday': 5, 'Saturday': 6
    };
    
    // Normalize the input date to local timezone (no time component)
    const checkDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    checkDate.setHours(0, 0, 0, 0);
    const selectedDay = checkDate.getDay();
    
    return this.sessions().filter(session => {
      // Check if day of week matches
      const sessionDay = dayMap[session.day_of_week];
      if (sessionDay !== selectedDay) return false;
      
      // Check date range - parse date strings in local timezone
      if (session.start_date) {
        // Parse date string in local timezone to avoid UTC conversion
        const dateParts = session.start_date.split('-');
        const startYear = parseInt(dateParts[0], 10);
        const startMonth = parseInt(dateParts[1], 10) - 1;
        const startDay = parseInt(dateParts[2], 10);
        const startDate = new Date(startYear, startMonth, startDay);
        startDate.setHours(0, 0, 0, 0);
        
        // Date must be on or after start_date
        if (checkDate < startDate) return false;
      }
      
      if (session.end_date) {
        // Parse date string in local timezone to avoid UTC conversion
        const dateParts = session.end_date.split('-');
        const endYear = parseInt(dateParts[0], 10);
        const endMonth = parseInt(dateParts[1], 10) - 1;
        const endDay = parseInt(dateParts[2], 10);
        const endDate = new Date(endYear, endMonth, endDay);
        endDate.setHours(0, 0, 0, 0);
        
        // Date must be on or before end_date
        if (checkDate > endDate) return false;
      }
      
      return true;
    });
  }
}
