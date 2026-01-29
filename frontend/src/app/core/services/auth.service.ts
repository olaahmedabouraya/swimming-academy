import { Injectable, signal } from '@angular/core';
import { Router } from '@angular/router';
import { Observable, tap } from 'rxjs';
import { ApiService } from './api.service';
import { User, AuthResponse } from '../models';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  currentUser = signal<User | null>(null);
  token = signal<string | null>(null);

  constructor(
    private apiService: ApiService,
    private router: Router
  ) {
    this.loadStoredAuth();
  }

  private loadStoredAuth() {
    const token = localStorage.getItem('token');
    const user = localStorage.getItem('user');
    
    if (token && user) {
      this.token.set(token);
      this.currentUser.set(JSON.parse(user));
    }
  }

  login(email: string, password: string): Observable<AuthResponse> {
    return this.apiService.post<AuthResponse>('/login', { email, password })
      .pipe(
        tap(response => {
          this.token.set(response.token);
          this.currentUser.set(response.user);
          localStorage.setItem('token', response.token);
          localStorage.setItem('user', JSON.stringify(response.user));
        })
      );
  }

  register(data: any): Observable<AuthResponse> {
    return this.apiService.post<AuthResponse>('/register', data)
      .pipe(
        tap(response => {
          this.token.set(response.token);
          this.currentUser.set(response.user);
          localStorage.setItem('token', response.token);
          localStorage.setItem('user', JSON.stringify(response.user));
        })
      );
  }

  logout() {
    this.apiService.post('/logout', {}).subscribe();
    this.token.set(null);
    this.currentUser.set(null);
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.router.navigate(['/login']);
  }

  isAuthenticated(): boolean {
    return !!this.token();
  }

  isManager(): boolean {
    const user = this.currentUser();
    return user?.role === 'manager' || user?.role === 'admin';
  }

  isPlayer(): boolean {
    return this.currentUser()?.role === 'player';
  }

  getMe(): Observable<User> {
    return this.apiService.get<User>('/me').pipe(
      tap(user => {
        this.currentUser.set(user);
        localStorage.setItem('user', JSON.stringify(user));
      })
    );
  }
}

