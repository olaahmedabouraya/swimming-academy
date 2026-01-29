import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { MonthlyRecordService } from '../../../core/services/monthly-record.service';
import { MonthlyRecordFilters } from '../../../core/models';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-monthly-records',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent],
  templateUrl: './monthly-records.component.html',
  styleUrls: ['./monthly-records.component.scss']
})
export class MonthlyRecordsComponent implements OnInit {
  records = signal<any[]>([]);
  loading = signal(true);
  filterYear = '';
  filterMonth = '';
  showAddModal = signal(false);
  newRecord = signal({
    year: new Date().getFullYear(),
    month: new Date().getMonth() + 1,
    branch_id: null as number | null,
    revenue: 0,
    new_enrollments: 0,
    total_active_players: 0,
    selling_rate: 0,
    total_sessions_conducted: 0,
    total_attendance: 0,
    notes: ''
  });
  saving = signal(false);
  years = Array.from({length: 10}, (_, i) => new Date().getFullYear() - i);
  months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  window = window;

  constructor(
    public authService: AuthService,
    private monthlyRecordService: MonthlyRecordService
  ) {}

  ngOnInit() {
    this.loadRecords();
  }

  loadRecords() {
    this.loading.set(true);
    const filters: MonthlyRecordFilters = {};
    
    if (this.filterYear) filters.year = parseInt(this.filterYear);
    if (this.filterMonth) filters.month = parseInt(this.filterMonth);

    this.monthlyRecordService.getAllRecords(filters).subscribe({
      next: (data: any) => {
        this.records.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading records:', err);
        this.loading.set(false);
      }
    });
  }

  getMonthName(month: number): string {
    return this.months[month - 1] || '';
  }

  openAddModal() {
    this.showAddModal.set(true);
  }

  closeAddModal() {
    this.showAddModal.set(false);
    this.newRecord.set({
      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
      branch_id: null,
      revenue: 0,
      new_enrollments: 0,
      total_active_players: 0,
      selling_rate: 0,
      total_sessions_conducted: 0,
      total_attendance: 0,
      notes: ''
    });
  }

  saveRecord() {
    this.saving.set(true);
    const recordData: any = {
      year: this.newRecord().year,
      month: this.newRecord().month,
      revenue: this.newRecord().revenue,
      new_enrollments: this.newRecord().new_enrollments,
      total_active_players: this.newRecord().total_active_players,
      selling_rate: this.newRecord().selling_rate,
      total_sessions_conducted: this.newRecord().total_sessions_conducted,
      total_attendance: this.newRecord().total_attendance,
      notes: this.newRecord().notes
    };
    if (this.newRecord().branch_id) {
      recordData.branch_id = this.newRecord().branch_id;
    }
    
    this.monthlyRecordService.createRecord(recordData).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadRecords();
      },
      error: (err) => {
        console.error('Error creating record:', err);
        window.alert('Failed to create record. Please try again.');
        this.saving.set(false);
      }
    });
  }
}
