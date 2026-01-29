import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-player-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink, LogoComponent],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.scss'
})
export class DashboardComponent implements OnInit {
  playerData = signal<any>(null);
  loading = signal(true);

  constructor(
    public authService: AuthService,
    private playerService: PlayerService
  ) {}

  ngOnInit() {
    this.loadPlayerData();
  }

  loadPlayerData() {
    this.loading.set(true);
    this.playerService.getMyProfile().subscribe({
      next: (data: any) => {
        this.playerData.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading player data:', err);
        this.loading.set(false);
      }
    });
  }

  logout() {
    this.authService.logout();
  }
}
