import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { CoachService } from '../../../core/services/coach.service';
import { CoachAttendanceService } from '../../../core/services/coach-attendance.service';
import { PlayerService } from '../../../core/services/player.service';
import { BranchService } from '../../../core/services/branch.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';
import { Coach, CoachStats } from '../../../core/models';

@Component({
  selector: 'app-coaches',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent],
  templateUrl: './coaches.component.html',
  styleUrl: './coaches.component.scss'
})
export class CoachesComponent implements OnInit {
  coaches = signal<Coach[]>([]);
  players = signal<any[]>([]);
  branches = signal<any[]>([]);
  loading = signal(true);
  showAddModal = signal(false);
  showStatsModal = signal(false);
  selectedCoach = signal<Coach | null>(null);
  editingCoach = signal<Coach | null>(null);
  coachStats = signal<CoachStats | null>(null);
  statsStartDate = signal(new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0]);
  statsEndDate = signal(new Date().toISOString().split('T')[0]);
  
  newCoach = signal({
    user_id: null as number | null,
    branch_id: null as number | null,
    specialization: '',
    hourly_rate: 0,
    notes: '',
    is_active: true
  });
  
  saving = signal(false);
  loadingStats = signal(false);
  window = window;

  constructor(
    public authService: AuthService,
    private coachService: CoachService,
    private playerService: PlayerService,
    private branchService: BranchService
  ) {}

  ngOnInit() {
    this.loadBranches();
    this.loadPlayers();
    this.loadCoaches();
  }

  loadBranches() {
    this.branchService.getAllBranches().subscribe({
      next: (data: any) => {
        this.branches.set(data);
      },
      error: (err) => {
        console.error('Error loading branches:', err);
      }
    });
  }

  loadPlayers() {
    this.playerService.getAllPlayers().subscribe({
      next: (data: any) => {
        this.players.set(data);
      },
      error: (err) => {
        console.error('Error loading players:', err);
      }
    });
  }

  loadCoaches() {
    this.loading.set(true);
    this.coachService.getAllCoaches().subscribe({
      next: (data: any) => {
        this.coaches.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading coaches:', err);
        this.loading.set(false);
      }
    });
  }

  openAddModal() {
    this.showAddModal.set(true);
  }

  closeAddModal() {
    this.showAddModal.set(false);
    this.editingCoach.set(null);
    this.newCoach.set({
      user_id: null,
      branch_id: null,
      specialization: '',
      hourly_rate: 0,
      notes: '',
      is_active: true
    });
  }

  openEditModal(coach: Coach) {
    this.editingCoach.set(coach);
    this.newCoach.set({
      user_id: coach.user_id || null,
      branch_id: coach.branch_id || null,
      specialization: coach.specialization || '',
      hourly_rate: coach.hourly_rate || 0,
      notes: coach.notes || '',
      is_active: coach.is_active
    });
    this.showAddModal.set(true);
  }

  openStatsModal(coach: Coach) {
    this.selectedCoach.set(coach);
    this.showStatsModal.set(true);
    this.loadCoachStats(coach.id!);
  }

  closeStatsModal() {
    this.showStatsModal.set(false);
    this.selectedCoach.set(null);
    this.coachStats.set(null);
  }

  loadCoachStats(coachId: number) {
    this.loadingStats.set(true);
    this.coachService.getCoachStats(coachId, this.statsStartDate(), this.statsEndDate()).subscribe({
      next: (data: any) => {
        // Calculate salary instantly: hourly_rate * total_hours
        const coach = this.coaches().find(c => c.id === coachId);
        if (coach && data.total_hours) {
          data.calculated_salary = coach.hourly_rate * data.total_hours;
        }
        this.coachStats.set(data);
        this.loadingStats.set(false);
      },
      error: (err) => {
        console.error('Error loading coach stats:', err);
        this.loadingStats.set(false);
      }
    });
  }

  onStatsDateChange() {
    if (this.selectedCoach()) {
      this.loadCoachStats(this.selectedCoach()!.id!);
    }
  }

  saveCoach() {
    if (!this.newCoach().user_id || !this.newCoach().hourly_rate) {
      window.alert('Please fill in all required fields');
      return;
    }

    this.saving.set(true);
    const coachData = {
      ...this.newCoach(),
      user_id: this.newCoach().user_id ?? undefined,
      branch_id: this.newCoach().branch_id ?? undefined
    };
    this.coachService.createCoach(coachData).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadCoaches();
      },
      error: (err) => {
        console.error('Error creating coach:', err);
        window.alert('Failed to create coach. Please try again.');
        this.saving.set(false);
      }
    });
  }

  updateCoach() {
    const coach = this.editingCoach();
    if (!coach || !coach.id) return;

    if (!this.newCoach().user_id || !this.newCoach().hourly_rate) {
      window.alert('Please fill in all required fields');
      return;
    }

    this.saving.set(true);
    const coachData = {
      ...this.newCoach(),
      user_id: this.newCoach().user_id ?? undefined,
      branch_id: this.newCoach().branch_id ?? undefined
    };
    this.coachService.updateCoach(coach.id, coachData).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadCoaches();
      },
      error: (err) => {
        console.error('Error updating coach:', err);
        window.alert('Failed to update coach. Please try again.');
        this.saving.set(false);
      }
    });
  }

  assignPlayer(coachId: number, playerId: number) {
    this.coachService.assignPlayer(coachId, playerId).subscribe({
      next: () => {
        this.loadCoaches();
        window.alert('Player assigned successfully!');
      },
      error: (err) => {
        console.error('Error assigning player:', err);
        window.alert('Failed to assign player.');
      }
    });
  }

  removePlayer(playerId: number) {
    if (window.confirm('Remove player from coach?')) {
      this.coachService.removePlayer(playerId).subscribe({
        next: () => {
          this.loadCoaches();
          window.alert('Player removed successfully!');
        },
        error: (err) => {
          console.error('Error removing player:', err);
          window.alert('Failed to remove player.');
        }
      });
    }
  }

  deleteCoach(id: number) {
    if (window.confirm('Are you sure you want to delete this coach?')) {
      this.coachService.deleteCoach(id).subscribe({
        next: () => {
          this.loadCoaches();
        },
        error: (err) => {
          console.error('Error deleting coach:', err);
          window.alert('Failed to delete coach.');
        }
      });
    }
  }
}
