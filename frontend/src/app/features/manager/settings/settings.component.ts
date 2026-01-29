import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { SettingService } from '../../../core/services/setting.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-settings',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent],
  templateUrl: './settings.component.html',
  styleUrl: './settings.component.scss'
})
export class SettingsComponent implements OnInit {
  loading = signal(true);
  saving = signal(false);
  periodStartDate = signal('');
  periodEndDate = signal('');
  window = window;

  constructor(
    public authService: AuthService,
    private settingService: SettingService
  ) {}

  ngOnInit() {
    this.loadSettings();
  }

  loadSettings() {
    this.loading.set(true);
    this.settingService.getAllSettings().subscribe({
      next: (data: any) => {
        this.periodStartDate.set(data.period_start_date?.value || '');
        this.periodEndDate.set(data.period_end_date?.value || '');
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading settings:', err);
        this.loading.set(false);
      }
    });
  }

  savePeriodDates() {
    if (!this.periodStartDate() || !this.periodEndDate()) {
      window.alert('Please fill in both period start and end dates');
      return;
    }

    if (new Date(this.periodStartDate()) >= new Date(this.periodEndDate())) {
      window.alert('Period end date must be after start date');
      return;
    }

    this.saving.set(true);
    this.settingService.updatePeriodDates(this.periodStartDate(), this.periodEndDate()).subscribe({
      next: () => {
        window.alert('Period dates updated successfully!');
        this.saving.set(false);
      },
      error: (err) => {
        console.error('Error updating period dates:', err);
        const errorMsg = err.error?.message || 'Failed to update period dates. Please try again.';
        window.alert(errorMsg);
        this.saving.set(false);
      }
    });
  }
}

