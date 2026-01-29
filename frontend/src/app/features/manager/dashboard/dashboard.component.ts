import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { BranchService } from '../../../core/services/branch.service';
import { MonthlyRecordService } from '../../../core/services/monthly-record.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-manager-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, LogoComponent],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {
  stats = signal<any>({});
  loading = signal(true);

  constructor(
    public authService: AuthService,
    private playerService: PlayerService,
    private branchService: BranchService,
    private monthlyRecordService: MonthlyRecordService
  ) {}

  ngOnInit() {
    this.loadStats();
  }

  loadStats() {
    this.loading.set(true);
    
    Promise.all([
      this.playerService.getAllPlayers().toPromise(),
      this.branchService.getAllBranches().toPromise(),
      this.monthlyRecordService.getStatistics().toPromise()
    ]).then(([players, branches, statistics]: any[]) => {
      this.stats.set({
        totalPlayers: players?.length || 0,
        totalBranches: branches?.length || 0,
        totalRevenue: statistics?.total_revenue || 0,
        avgSellingRate: statistics?.average_selling_rate || 0
      });
      this.loading.set(false);
    }).catch(err => {
      console.error('Error loading stats:', err);
      this.loading.set(false);
    });
  }
}
