import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-ratings',
  standalone: true,
  imports: [CommonModule, RouterLink, LogoComponent],
  templateUrl: './ratings.component.html',
  styleUrls: ['./ratings.component.scss']
})
export class RatingsComponent implements OnInit {
  ratings = signal<any[]>([]);
  averageRating = signal(0);
  loading = signal(true);

  constructor(
    public authService: AuthService,
    private playerService: PlayerService
  ) {}

  ngOnInit() {
    this.loadRatings();
  }

  loadRatings() {
    this.loading.set(true);
    this.playerService.getMyProfile().subscribe({
      next: (data: any) => {
        this.ratings.set(data.ratings || []);
        this.averageRating.set(data.average_rating || 0);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading ratings:', err);
        this.loading.set(false);
      }
    });
  }

  getStars(rating: number): number[] {
    return Array(Math.round(rating / 20)).fill(0);
  }
}
