import { Component, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { LogoComponent } from '../../../core/components/logo/logo.component';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink, LogoComponent],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent {
  formData = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'player' as 'player' | 'manager',
    phone: ''
  };
  
  loading = signal(false);
  error = signal('');

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onSubmit() {
    this.loading.set(true);
    this.error.set('');

    this.authService.register(this.formData).subscribe({
      next: (response) => {
        this.loading.set(false);
        if (response.user.role === 'player') {
          this.router.navigate(['/player']);
        } else {
          this.router.navigate(['/manager']);
        }
      },
      error: (err) => {
        this.loading.set(false);
        console.error('Registration error:', err);
        
        let errorMsg = 'Registration failed. Please try again.';
        
        if (err.error) {
          // Handle validation errors
          if (err.error.errors) {
            const errors = err.error.errors;
            const errorMessages = Object.keys(errors).map(key => {
              return Array.isArray(errors[key]) ? errors[key].join(', ') : errors[key];
            });
            errorMsg = errorMessages.join('; ');
          } else if (err.error.message) {
            errorMsg = err.error.message;
          }
        } else if (err.message) {
          errorMsg = err.message;
        }
        
        this.error.set(errorMsg);
      }
    });
  }
}
