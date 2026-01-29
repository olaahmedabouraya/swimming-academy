import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-attendance',
  standalone: true,
  imports: [CommonModule, RouterLink, LogoComponent],
  templateUrl: './attendance.component.html',
  styleUrls: ['./attendance.component.scss']
})
export class AttendanceComponent implements OnInit {
  attendances = signal<any[]>([]);
  loading = signal(true);
  totalPresent = signal(0);
  attendanceRate = signal(0);

  constructor(
    public authService: AuthService,
    private playerService: PlayerService
  ) {}

  ngOnInit() {
    this.loadAttendance();
  }

  loadAttendance() {
    this.loading.set(true);
    this.playerService.getMyProfile().subscribe({
      next: (data: any) => {
        this.attendances.set(data.attendances || []);
        const present = this.attendances().filter(a => a.status === 'present').length;
        this.totalPresent.set(present);
        const total = this.attendances().length;
        this.attendanceRate.set(total > 0 ? Math.round((present / total) * 100) : 0);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading attendance:', err);
        this.loading.set(false);
      }
    });
  }
}
