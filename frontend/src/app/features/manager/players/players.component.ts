import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { PlayerService } from '../../../core/services/player.service';
import { TrainingSessionService } from '../../../core/services/training-session.service';
import { BranchService } from '../../../core/services/branch.service';
import { FamilyRelationshipService, FamilyRelationship } from '../../../core/services/family-relationship.service';
import { CoachService } from '../../../core/services/coach.service';
import { PlayerFilters } from '../../../core/models';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-players',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent],
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent implements OnInit {
  players = signal<any[]>([]);
  loading = signal(true);
  filterLevel = '';
  showAddModal = signal(false);
  showMoveSessionModal = signal(false);
  showNotesModal = signal(false);
  showFamilyModal = signal(false);
  showCoachModal = signal(false);
  selectedPlayerForMove = signal<any>(null);
  selectedPlayerForNotes = signal<any>(null);
  selectedPlayerForFamily = signal<any>(null);
  selectedPlayerForCoach = signal<any>(null);
  coaches = signal<any[]>([]);
  familyRelationships = signal<FamilyRelationship[]>([]);
  sportsManagerNotes = signal('');
  savingNotes = signal(false);
  loadingFamily = signal(false);
  selectedCoachId = signal<number | null>(null);
  assigningCoach = signal(false);
  newRelationship = signal({
    related_player_id: null as number | null,
    relationship_type: 'sibling' as 'sibling' | 'parent',
    discount_percentage: 10
  });
  savingRelationship = signal(false);
  availableSessions = signal<any[]>([]);
  branches = signal<any[]>([]);
  selectedSessionId = signal<number | null>(null);
  moving = signal(false);
  activeMenuPlayerId = signal<number | null>(null);
  newPlayer = signal({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'player' as 'player' | 'manager',
    phone: '',
    date_of_birth: '',
    address: '',
    branch_id: null as number | null,
    level: 'beginner' as 'beginner' | 'intermediate' | 'advanced' | 'professional',
    enrollment_date: new Date().toISOString().split('T')[0],
    enrollment_type: 'monthly' as 'monthly' | 'per_session',
    sessions_per_month: 8,
    current_session_id: null as number | null,
    coach_id: null as number | null,
    medical_notes: '',
    emergency_contact: ''
  });
  saving = signal(false);
  window = window;

  constructor(
    public authService: AuthService,
    private playerService: PlayerService,
    private sessionService: TrainingSessionService,
    private branchService: BranchService,
    private familyService: FamilyRelationshipService,
    private coachService: CoachService
  ) {}

  ngOnInit() {
    this.loadPlayers();
    this.loadBranches();
    this.loadCoaches();
    this.loadSessions();
    // Close dropdown when clicking outside
    document.addEventListener('click', () => {
      this.closeActionsMenu();
    });
  }

  loadSessions() {
    this.sessionService.getAllSessions({}).subscribe({
      next: (data: any) => {
        this.availableSessions.set(data);
      },
      error: (err) => {
        console.error('Error loading sessions:', err);
      }
    });
  }

  loadCoaches() {
    this.coachService.getAllCoaches().subscribe({
      next: (data: any) => {
        this.coaches.set(data);
      },
      error: (err) => {
        console.error('Error loading coaches:', err);
      }
    });
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
    this.loading.set(true);
    const filters: PlayerFilters = {};
    
    if (this.filterLevel) filters.level = this.filterLevel;

    this.playerService.getAllPlayers(filters).subscribe({
      next: (data: any) => {
        this.players.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading players:', err);
        this.loading.set(false);
      }
    });
  }

  openAddModal() {
    this.showAddModal.set(true);
  }

  closeAddModal() {
    this.showAddModal.set(false);
    const today = new Date();
    this.newPlayer.set({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      role: 'player',
      phone: '',
      date_of_birth: '',
      address: '',
      branch_id: null,
      level: 'beginner',
      enrollment_date: today.toISOString().split('T')[0],
      enrollment_type: 'monthly',
      sessions_per_month: 8,
      current_session_id: null,
      coach_id: null,
      medical_notes: '',
      emergency_contact: ''
    });
  }

  savePlayer() {
    if (!this.newPlayer().name || !this.newPlayer().email || !this.newPlayer().password) {
      window.alert('Please fill in all required fields (Name, Email, Password)');
      return;
    }

    if (this.newPlayer().password !== this.newPlayer().password_confirmation) {
      window.alert('Passwords do not match');
      return;
    }

    this.saving.set(true);
    
    // Prepare data for the create-with-user endpoint
    const playerData: any = {
      name: this.newPlayer().name,
      email: this.newPlayer().email,
      password: this.newPlayer().password,
      password_confirmation: this.newPlayer().password_confirmation,
      phone: this.newPlayer().phone || null,
      date_of_birth: this.newPlayer().date_of_birth || null,
      address: this.newPlayer().address || null,
      branch_id: this.newPlayer().branch_id || null,
      level: this.newPlayer().level,
      enrollment_date: this.newPlayer().enrollment_date,
      enrollment_type: this.newPlayer().enrollment_type,
      sessions_per_month: this.newPlayer().enrollment_type === 'monthly' ? this.newPlayer().sessions_per_month : null,
      current_session_id: this.newPlayer().current_session_id || null,
      coach_id: this.newPlayer().coach_id || null,
      medical_notes: this.newPlayer().medical_notes || null,
      emergency_contact: this.newPlayer().emergency_contact || null,
    };

    this.playerService.createPlayerWithUser(playerData).subscribe({
      next: () => {
        this.saving.set(false);
        window.alert('Player created successfully!');
        this.closeAddModal();
        this.loadPlayers();
      },
      error: (err) => {
        console.error('Error creating player:', err);
        const errorMsg = err.error?.message || err.error?.errors || 'Failed to create player. Please try again.';
        window.alert(typeof errorMsg === 'string' ? errorMsg : JSON.stringify(errorMsg));
        this.saving.set(false);
      }
    });
  }

  openMoveSessionModal(player: any) {
    this.selectedPlayerForMove.set(player);
    this.showMoveSessionModal.set(true);
    this.loadAvailableSessions();
  }

  closeMoveSessionModal() {
    this.showMoveSessionModal.set(false);
    this.selectedPlayerForMove.set(null);
    this.selectedSessionId.set(null);
  }

  loadAvailableSessions() {
    this.sessionService.getAllSessions({}).subscribe({
      next: (data: any) => {
        // Filter out the player's current session
        const currentSessionId = this.selectedPlayerForMove()?.training_session_id;
        this.availableSessions.set(data.filter((s: any) => s.id !== currentSessionId));
      },
      error: (err) => {
        console.error('Error loading sessions:', err);
      }
    });
  }

  movePlayerSession() {
    const player = this.selectedPlayerForMove();
    const sessionId = this.selectedSessionId();
    
    if (!player || !sessionId) {
      window.alert('Please select a session');
      return;
    }

    this.moving.set(true);
    this.playerService.moveSession(player.id, sessionId).subscribe({
      next: () => {
        this.moving.set(false);
        window.alert('Player moved to new session successfully!');
        this.closeMoveSessionModal();
        this.loadPlayers();
      },
      error: (err) => {
        console.error('Error moving player session:', err);
        window.alert('Failed to move player. Please try again.');
        this.moving.set(false);
      }
    });
  }

  openNotesModal(player: any) {
    this.selectedPlayerForNotes.set(player);
    this.sportsManagerNotes.set(player.sports_manager_notes || '');
    this.showNotesModal.set(true);
  }

  closeNotesModal() {
    this.showNotesModal.set(false);
    this.selectedPlayerForNotes.set(null);
    this.sportsManagerNotes.set('');
  }

  saveSportsManagerNotes() {
    const player = this.selectedPlayerForNotes();
    if (!player) return;

    this.savingNotes.set(true);
    this.playerService.updateSportsManagerNotes(player.id, this.sportsManagerNotes()).subscribe({
      next: () => {
        this.savingNotes.set(false);
        window.alert('Notes saved successfully!');
        this.closeNotesModal();
        this.loadPlayers();
      },
      error: (err) => {
        console.error('Error saving notes:', err);
        window.alert('Failed to save notes. Please try again.');
        this.savingNotes.set(false);
      }
    });
  }

  openFamilyModal(player: any) {
    this.selectedPlayerForFamily.set(player);
    this.showFamilyModal.set(true);
    this.loadFamilyRelationships(player.id);
  }

  closeFamilyModal() {
    this.showFamilyModal.set(false);
    this.selectedPlayerForFamily.set(null);
    this.familyRelationships.set([]);
    this.newRelationship.set({
      related_player_id: null,
      relationship_type: 'sibling',
      discount_percentage: 10
    });
  }

  loadFamilyRelationships(playerId: number) {
    this.loadingFamily.set(true);
    this.familyService.getRelationshipsForPlayer(playerId).subscribe({
      next: (data: any) => {
        this.familyRelationships.set(data);
        this.loadingFamily.set(false);
      },
      error: (err) => {
        console.error('Error loading family relationships:', err);
        this.loadingFamily.set(false);
      }
    });
  }

  saveFamilyRelationship() {
    const player = this.selectedPlayerForFamily();
    const relatedPlayerId = this.newRelationship().related_player_id;
    if (!player || !relatedPlayerId) {
      window.alert('Please select a related player');
      return;
    }

    this.savingRelationship.set(true);
    this.familyService.createRelationship({
      player_id: player.id,
      related_player_id: relatedPlayerId,
      relationship_type: this.newRelationship().relationship_type,
      discount_percentage: this.newRelationship().discount_percentage
    }).subscribe({
      next: () => {
        this.savingRelationship.set(false);
        window.alert('Family relationship added successfully!');
        this.newRelationship.set({
          related_player_id: null,
          relationship_type: 'sibling',
          discount_percentage: 10
        });
        this.loadFamilyRelationships(player.id);
      },
      error: (err) => {
        console.error('Error creating relationship:', err);
        window.alert('Failed to add relationship. Please try again.');
        this.savingRelationship.set(false);
      }
    });
  }

  deleteFamilyRelationship(id: number) {
    if (window.confirm('Are you sure you want to delete this relationship?')) {
      this.familyService.deleteRelationship(id).subscribe({
        next: () => {
          const player = this.selectedPlayerForFamily();
          if (player) {
            this.loadFamilyRelationships(player.id);
          }
        },
        error: (err) => {
          console.error('Error deleting relationship:', err);
          window.alert('Failed to delete relationship.');
        }
      });
    }
  }

  openCoachModal(player: any) {
    this.selectedPlayerForCoach.set(player);
    this.selectedCoachId.set(player.coach_id || null);
    this.showCoachModal.set(true);
  }

  closeCoachModal() {
    this.showCoachModal.set(false);
    this.selectedPlayerForCoach.set(null);
    this.selectedCoachId.set(null);
  }

  assignCoachToPlayer() {
    const player = this.selectedPlayerForCoach();
    const coachId = this.selectedCoachId();
    
    if (!player) return;

    this.assigningCoach.set(true);
    const updateData: any = { coach_id: coachId ?? undefined };
    
    this.playerService.updatePlayer(player.id, updateData).subscribe({
      next: () => {
        this.assigningCoach.set(false);
        window.alert('Coach assigned successfully!');
        this.closeCoachModal();
        this.loadPlayers();
      },
      error: (err) => {
        console.error('Error assigning coach:', err);
        window.alert('Failed to assign coach. Please try again.');
        this.assigningCoach.set(false);
      }
    });
  }

  removeCoachFromPlayer(player: any) {
    if (window.confirm('Remove coach from this player?')) {
      this.playerService.updatePlayer(player.id, { coach_id: undefined }).subscribe({
        next: () => {
          window.alert('Coach removed successfully!');
          this.loadPlayers();
        },
        error: (err) => {
          console.error('Error removing coach:', err);
          window.alert('Failed to remove coach.');
        }
      });
    }
  }

  toggleActionsMenu(event: Event, player: any) {
    event.stopPropagation();
    if (this.activeMenuPlayerId() === player.id) {
      this.activeMenuPlayerId.set(null);
    } else {
      this.activeMenuPlayerId.set(player.id);
    }
  }

  closeActionsMenu() {
    this.activeMenuPlayerId.set(null);
  }
}
