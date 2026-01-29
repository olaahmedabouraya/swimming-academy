import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [CommonModule, RouterLink, LogoComponent],
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  playerData = signal<any>(null);
  loading = signal(true);

  constructor(
    public authService: AuthService,
    private playerService: PlayerService
  ) {}

  ngOnInit() {
    this.loadProfile();
  }

  loadProfile() {
    this.loading.set(true);
    this.playerService.getMyProfile().subscribe({
      next: (data: any) => {
        this.playerData.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading profile:', err);
        this.loading.set(false);
      }
    });
  }
}
