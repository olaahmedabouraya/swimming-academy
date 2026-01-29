import { Component, Input, Output, EventEmitter, signal, computed, effect, OnChanges, SimpleChanges, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-calendar',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="calendar-container">
      <div class="calendar-header">
        <button class="btn-nav" (click)="previousMonth()">‹</button>
        <h3>{{ currentMonthYear() }}</h3>
        <button class="btn-nav" (click)="nextMonth()">›</button>
      </div>
      
      <div class="calendar-weekdays">
        <div *ngFor="let day of weekDays" class="weekday">{{ day }}</div>
      </div>
      
      <div class="calendar-grid">
        <div 
          *ngFor="let day of calendarDays()" 
          class="calendar-day"
          [class.other-month]="day.isOtherMonth"
          [class.today]="day.isToday"
          [class.selected]="day.isSelected"
          [class.has-events]="day.hasEvents"
          (click)="selectDay(day.date)"
        >
          <span class="day-number">{{ day.day }}</span>
          <div *ngIf="day.hasEvents" class="events-indicator">
            <span *ngFor="let event of day.events" class="event-dot" [style.background-color]="event.color"></span>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .calendar-container {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      
      h3 {
        margin: 0;
        color: var(--primary-color);
        font-size: 20px;
      }
    }

    .btn-nav {
      background: var(--primary-color);
      color: white;
      border: none;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s;

      &:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
      }
    }

    .calendar-weekdays {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      margin-bottom: 10px;
      
      .weekday {
        text-align: center;
        font-weight: 600;
        color: var(--gray-color);
        font-size: 12px;
        text-transform: uppercase;
        padding: 8px 0;
      }
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
    }

    .calendar-day {
      aspect-ratio: 1;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      padding: 8px;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      position: relative;
      background: white;

      &:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      &.other-month {
        opacity: 0.3;
        background: #f5f5f5;
      }

      &.today {
        border-color: var(--primary-color);
        background: rgba(0, 102, 204, 0.1);
      }

      &.selected {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
      }

      &.has-events {
        border-color: var(--success-color);
      }

      .day-number {
        font-weight: 600;
        font-size: 14px;
      }

      .events-indicator {
        display: flex;
        gap: 3px;
        margin-top: 4px;
        flex-wrap: wrap;
        justify-content: center;
      }

      .event-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
      }
    }
  `]
})
export class CalendarComponent implements OnChanges, OnInit {
  @Input() selectedDate: Date | null = null;
  @Input() events: Array<{ date: Date; color?: string; title?: string }> = [];
  @Output() dateSelected = new EventEmitter<Date>();

  currentDate = signal(new Date());
  selectedDateSignal = signal<Date | null>(null);
  weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  ngOnInit() {
    // Initialize the signal with the current selectedDate
    this.updateSelectedDateSignal();
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes['selectedDate']) {
      this.updateSelectedDateSignal();
    }
  }

  private updateSelectedDateSignal() {
    if (this.selectedDate) {
      // Create date in local timezone to avoid timezone shifts
      const date = this.selectedDate instanceof Date 
        ? new Date(this.selectedDate.getFullYear(), this.selectedDate.getMonth(), this.selectedDate.getDate())
        : new Date(this.selectedDate);
      this.selectedDateSignal.set(new Date(date.getFullYear(), date.getMonth(), date.getDate()));
    } else {
      this.selectedDateSignal.set(null);
    }
  }

  currentMonthYear = computed(() => {
    const date = this.currentDate();
    return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
  });

  calendarDays = computed(() => {
    const date = this.currentDate();
    const year = date.getFullYear();
    const month = date.getMonth();
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - startDate.getDay());
    
    const days: Array<{
      date: Date;
      day: number;
      isOtherMonth: boolean;
      isToday: boolean;
      isSelected: boolean;
      hasEvents: boolean;
      events: Array<{ date: Date; color?: string; title?: string }>;
    }> = [];
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Use the signal for reactive updates - this will trigger recomputation when it changes
    const selected = this.selectedDateSignal();
    let selectedNormalized: Date | null = null;
    if (selected) {
      selectedNormalized = new Date(selected.getFullYear(), selected.getMonth(), selected.getDate());
      selectedNormalized.setHours(0, 0, 0, 0);
    }
    
    for (let i = 0; i < 42; i++) {
      const currentDate = new Date(startDate);
      currentDate.setDate(startDate.getDate() + i);
      currentDate.setHours(0, 0, 0, 0);
      
      const isOtherMonth = currentDate.getMonth() !== month;
      const isToday = currentDate.getTime() === today.getTime();
      const isSelected = !!(selectedNormalized && currentDate.getTime() === selectedNormalized.getTime());
      
      const dayEvents = this.events.filter(event => {
        const eventDate = new Date(event.date);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate.getTime() === currentDate.getTime();
      });
      
      days.push({
        date: new Date(currentDate),
        day: currentDate.getDate(),
        isOtherMonth,
        isToday,
        isSelected,
        hasEvents: dayEvents.length > 0,
        events: dayEvents
      });
    }
    
    return days;
  });

  previousMonth() {
    const date = new Date(this.currentDate());
    // Set to first day of the month to avoid date overflow issues
    date.setDate(1);
    date.setMonth(date.getMonth() - 1);
    this.currentDate.set(date);
  }

  nextMonth() {
    const date = new Date(this.currentDate());
    // Set to first day of the month to avoid date overflow issues
    // (e.g., Jan 31 -> Feb 31 becomes March 3)
    date.setDate(1);
    date.setMonth(date.getMonth() + 1);
    this.currentDate.set(date);
  }

  selectDay(date: Date) {
    // Create a new date object in local timezone to avoid timezone issues
    const localDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    this.dateSelected.emit(localDate);
  }
}

