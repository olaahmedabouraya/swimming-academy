import { Component, OnInit, signal, ViewChild, ElementRef, AfterViewChecked } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { BranchService } from '../../../core/services/branch.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';
import { LocationInputComponent } from '../../../core/components/location-input/location-input.component';
import { LocationDisplayComponent } from '../../../core/components/location-display/location-display.component';

@Component({
  selector: 'app-branches',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, LogoComponent, LocationInputComponent, LocationDisplayComponent],
  templateUrl: './branches.component.html',
  styleUrls: ['./branches.component.scss']
})
export class BranchesComponent implements OnInit, AfterViewChecked {
  @ViewChild('addModalContent', { static: false }) addModalContent!: ElementRef<HTMLElement>;
  @ViewChild('editModalContent', { static: false }) editModalContent!: ElementRef<HTMLElement>;
  private shouldScrollToTop = false;
  branches = signal<any[]>([]);
  loading = signal(true);
  showAddModal = signal(false);
  showEditModal = signal(false);
  editingBranch = signal<any | null>(null);
  newBranch = signal({
    name: '',
    address: '',
    latitude: null as number | null,
    longitude: null as number | null,
    phone: '',
    email: '',
    manager_name: '',
    capacity: 50
  });
  saving = signal(false);
  window = window;

  constructor(
    public authService: AuthService,
    private branchService: BranchService
  ) {}

  ngOnInit() {
    this.loadBranches();
  }

  loadBranches() {
    this.loading.set(true);
    this.branchService.getAllBranches().subscribe({
      next: (data: any) => {
        this.branches.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading branches:', err);
        this.loading.set(false);
      }
    });
  }

  openAddModal() {
    // Reset all fields to empty/default values
    this.newBranch.set({
      name: '',
      address: '',
      latitude: null,
      longitude: null,
      phone: '',
      email: '',
      manager_name: '',
      capacity: 50
    });
    this.showAddModal.set(true);
    this.shouldScrollToTop = true;
  }

  closeAddModal() {
    this.showAddModal.set(false);
    this.newBranch.set({
      name: '',
      address: '',
      latitude: null,
      longitude: null,
      phone: '',
      email: '',
      manager_name: '',
      capacity: 50
    });
  }

  saveBranch() {
    this.saving.set(true);
    const branchData = {
      ...this.newBranch(),
      latitude: this.newBranch().latitude ?? undefined,
      longitude: this.newBranch().longitude ?? undefined
    };
    this.branchService.createBranch(branchData).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeAddModal();
        this.loadBranches();
      },
      error: (err) => {
        console.error('Error creating branch:', err);
        window.alert('Failed to create branch. Please try again.');
        this.saving.set(false);
      }
    });
  }

  openEditModal(branch: any) {
    // Store the branch being edited
    this.editingBranch.set({ 
      ...branch,
      latitude: branch.latitude || null,
      longitude: branch.longitude || null
    });
    // Pre-fill the form with current branch data
    this.newBranch.set({
      name: branch.name || '',
      address: branch.address || '',
      latitude: branch.latitude ?? null,
      longitude: branch.longitude ?? null,
      phone: branch.phone || '',
      email: branch.email || '',
      manager_name: branch.manager_name || '',
      capacity: branch.capacity || 50
    });
    this.showEditModal.set(true);
    this.shouldScrollToTop = true;
  }

  ngAfterViewChecked() {
    if (this.shouldScrollToTop) {
      setTimeout(() => {
        const modalContent = this.addModalContent?.nativeElement || this.editModalContent?.nativeElement;
        if (modalContent) {
          modalContent.scrollTop = 0;
        }
        this.shouldScrollToTop = false;
      }, 100);
    }
  }

  onLocationSelected(location: { latitude: number; longitude: number }, isEdit: boolean = false) {
    this.newBranch.update(branch => ({
      ...branch,
      latitude: location.latitude,
      longitude: location.longitude
    }));
  }

  closeEditModal() {
    this.showEditModal.set(false);
    this.editingBranch.set(null);
    // Reset form fields when closing edit modal
    this.newBranch.set({
      name: '',
      address: '',
      latitude: null,
      longitude: null,
      phone: '',
      email: '',
      manager_name: '',
      capacity: 50
    });
  }

  updateBranch() {
    const branch = this.editingBranch();
    if (!branch || !branch.id) {
      window.alert('No branch selected for editing.');
      return;
    }

    this.saving.set(true);
    const updateData = {
      ...this.newBranch(),
      latitude: this.newBranch().latitude ?? undefined,
      longitude: this.newBranch().longitude ?? undefined
    };
    this.branchService.updateBranch(branch.id, updateData).subscribe({
      next: () => {
        this.saving.set(false);
        this.closeEditModal();
        this.loadBranches();
        window.alert('Branch updated successfully!');
      },
      error: (err) => {
        console.error('Error updating branch:', err);
        window.alert('Failed to update branch. Please try again.');
        this.saving.set(false);
      }
    });
  }

  deleteBranch(id: number) {
    if (!window.confirm('Are you sure you want to delete this branch? This action cannot be undone.')) {
      return;
    }

    this.branchService.deleteBranch(id).subscribe({
      next: () => {
        window.alert('Branch deleted successfully!');
        this.loadBranches();
      },
      error: (err) => {
        console.error('Error deleting branch:', err);
        window.alert('Failed to delete branch. Please try again.');
      }
    });
  }
}
