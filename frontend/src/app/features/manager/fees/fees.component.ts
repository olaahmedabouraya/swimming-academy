import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { FeeService } from '../../../core/services/fee.service';
import { PlayerService } from '../../../core/services/player.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';
import { CalendarComponent } from '../../../core/components/calendar/calendar.component';
import { Fee } from '../../../core/models';

@Component({
  selector: 'app-fees',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent, CalendarComponent],
  templateUrl: './fees.component.html',
  styleUrl: './fees.component.scss'
})
export class FeesComponent implements OnInit {
  fees = signal<Fee[]>([]);
  players = signal<any[]>([]);
  loading = signal(true);
  showAddModal = signal(false);
  showWithdrawalModal = signal(false);
  showRenewalModal = signal(false);
  
  revenueStartDate = signal(new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0]);
  revenueEndDate = signal(new Date().toISOString().split('T')[0]);
  totalRevenue = signal(0);
  
  newFee = signal({
    player_id: null as number | null,
    fee_type: 'registration' as 'registration' | 'renewal' | 'per_session' | 'withdrawal',
    amount: 0,
    payment_date: new Date().toISOString().split('T')[0],
    payment_method: 'cash' as 'cash' | 'card' | 'bank_transfer',
    reference_number: '',
    notes: ''
  });
  
  renewalData = signal({
    player_id: null as number | null,
    base_amount: 0,
    payment_date: new Date().toISOString().split('T')[0],
    notes: ''
  });
  
  saving = signal(false);
  loadingRevenue = signal(false);
  window = window;

  constructor(
    public authService: AuthService,
    private feeService: FeeService,
    private playerService: PlayerService
  ) {}

  ngOnInit() {
    this.loadPlayers();
    this.loadFees();
    this.loadRevenue();
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

  loadFees() {
    this.loading.set(true);
    this.feeService.getAllFees().subscribe({
      next: (data: any) => {
        this.fees.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading fees:', err);
        this.loading.set(false);
      }
    });
  }

  loadRevenue() {
    this.loadingRevenue.set(true);
    this.feeService.getRevenue(this.revenueStartDate(), this.revenueEndDate()).subscribe({
      next: (data: any) => {
        this.totalRevenue.set(data.total_revenue || 0);
        this.loadingRevenue.set(false);
      },
      error: (err) => {
        console.error('Error loading revenue:', err);
        this.loadingRevenue.set(false);
      }
    });
  }

  onRevenueDateChange() {
    this.loadRevenue();
  }

  onRevenueCalendarDateSelected(date: Date) {
    // Set date range to selected month
    const year = date.getFullYear();
    const month = date.getMonth();
    const startDate = new Date(year, month, 1);
    const endDate = new Date(year, month + 1, 0);
    
    this.revenueStartDate.set(startDate.toISOString().split('T')[0]);
    this.revenueEndDate.set(endDate.toISOString().split('T')[0]);
    this.loadRevenue();
  }

  openAddModal() {
    this.showAddModal.set(true);
  }

  openWithdrawalModal() {
    this.showWithdrawalModal.set(true);
  }

  openRenewalModal() {
    this.showRenewalModal.set(true);
  }

  closeAddModal() {
    this.showAddModal.set(false);
    this.newFee.set({
      player_id: null,
      fee_type: 'registration',
      amount: 0,
      payment_date: new Date().toISOString().split('T')[0],
      payment_method: 'cash',
      reference_number: '',
      notes: ''
    });
  }

  closeWithdrawalModal() {
    this.showWithdrawalModal.set(false);
    this.newFee.set({
      player_id: null,
      fee_type: 'withdrawal',
      amount: 0,
      payment_date: new Date().toISOString().split('T')[0],
      payment_method: 'cash',
      reference_number: '',
      notes: ''
    });
  }

  closeRenewalModal() {
    this.showRenewalModal.set(false);
    this.renewalData.set({
      player_id: null,
      base_amount: 0,
      payment_date: new Date().toISOString().split('T')[0],
      notes: ''
    });
  }

  saveFee() {
    if (!this.newFee().player_id || !this.newFee().amount) {
      window.alert('Please fill in all required fields');
      return;
    }

    this.saving.set(true);
    const feeData = {
      ...this.newFee(),
      player_id: this.newFee().player_id ?? undefined
    };
    this.feeService.createFee(feeData).subscribe({
      next: (response: any) => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadFees();
        // Instant revenue update
        if (response.revenue) {
          this.totalRevenue.set(response.revenue);
        } else {
          this.loadRevenue();
        }
      },
      error: (err) => {
        console.error('Error creating fee:', err);
        window.alert('Failed to create fee. Please try again.');
        this.saving.set(false);
      }
    });
  }

  saveWithdrawal() {
    if (!this.newFee().player_id || !this.newFee().amount) {
      window.alert('Please fill in all required fields');
      return;
    }

    this.saving.set(true);
    this.feeService.recordWithdrawal(
      this.newFee().player_id!,
      this.newFee().amount,
      this.newFee().payment_date,
      this.newFee().notes
    ).subscribe({
      next: (response: any) => {
        this.saving.set(false);
        this.closeWithdrawalModal();
        this.loadFees();
        // Instant revenue update
        if (response.revenue) {
          this.totalRevenue.set(response.revenue);
        } else {
          this.loadRevenue();
        }
      },
      error: (err) => {
        console.error('Error recording withdrawal:', err);
        window.alert('Failed to record withdrawal. Please try again.');
        this.saving.set(false);
      }
    });
  }

  saveRenewal() {
    if (!this.renewalData().player_id || !this.renewalData().base_amount) {
      window.alert('Please fill in all required fields');
      return;
    }

    this.saving.set(true);
    this.feeService.createRenewalWithDiscounts(
      this.renewalData().player_id!,
      this.renewalData().base_amount,
      this.renewalData().payment_date,
      this.renewalData().notes
    ).subscribe({
      next: (response: any) => {
        this.saving.set(false);
        this.closeRenewalModal();
        this.loadFees();
        // Instant revenue update
        if (response.revenue) {
          this.totalRevenue.set(response.revenue);
        } else {
          this.loadRevenue();
        }
      },
      error: (err) => {
        console.error('Error creating renewal:', err);
        window.alert('Failed to create renewal. Please try again.');
        this.saving.set(false);
      }
    });
  }

  deleteFee(id: number) {
    if (window.confirm('Are you sure you want to delete this fee?')) {
      this.feeService.deleteFee(id).subscribe({
        next: (response: any) => {
          this.loadFees();
          // Instant revenue update
          if (response?.revenue !== undefined) {
            this.totalRevenue.set(response.revenue);
          } else {
            this.loadRevenue();
          }
        },
        error: (err) => {
          console.error('Error deleting fee:', err);
          window.alert('Failed to delete fee.');
        }
      });
    }
  }
}
