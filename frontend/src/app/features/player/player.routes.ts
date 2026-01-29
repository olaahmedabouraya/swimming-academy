import { Routes } from '@angular/router';

export const playerRoutes: Routes = [
  {
    path: '',
    loadComponent: () => import('./dashboard/dashboard.component').then(m => m.DashboardComponent)
  },
  {
    path: 'profile',
    loadComponent: () => import('./profile/profile.component').then(m => m.ProfileComponent)
  },
  {
    path: 'schedule',
    loadComponent: () => import('./schedule/schedule.component').then(m => m.ScheduleComponent)
  },
  {
    path: 'attendance',
    loadComponent: () => import('./attendance/attendance.component').then(m => m.AttendanceComponent)
  },
  {
    path: 'ratings',
    loadComponent: () => import('./ratings/ratings.component').then(m => m.RatingsComponent)
  }
];


