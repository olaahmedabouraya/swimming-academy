import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-schedule',
  standalone: true,
  imports: [CommonModule, RouterLink, LogoComponent],
  templateUrl: './schedule.component.html',
  styleUrls: ['./schedule.component.scss']
})
export class ScheduleComponent implements OnInit {
  schedules = signal<any[]>([]);
  loading = signal(true);

  constructor(
    public authService: AuthService,
    private playerService: PlayerService
  ) {}

  ngOnInit() {
    this.loadSchedules();
  }

  loadSchedules() {
    this.loading.set(true);
    this.playerService.getMyProfile().subscribe({
      next: (data: any) => {
        this.schedules.set(data.schedules || []);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading schedules:', err);
        this.loading.set(false);
      }
    });
  }
}
