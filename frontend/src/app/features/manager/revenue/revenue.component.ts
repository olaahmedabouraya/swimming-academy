import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { MonthlyRecordService } from '../../../core/services/monthly-record.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-revenue',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, LogoComponent],
  templateUrl: './revenue.component.html',
  styleUrls: ['./revenue.component.scss']
})
export class RevenueComponent implements OnInit {
  statistics = signal<any>({});
  loading = signal(true);

  constructor(
    public authService: AuthService,
    private monthlyRecordService: MonthlyRecordService
  ) {}

  ngOnInit() {
    this.loadStatistics();
  }

  loadStatistics() {
    this.loading.set(true);
    this.monthlyRecordService.getStatistics().subscribe({
      next: (data: any) => {
        this.statistics.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading statistics:', err);
        this.loading.set(false);
      }
    });
  }
}
