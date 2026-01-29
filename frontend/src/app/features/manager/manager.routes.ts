import { Routes } from '@angular/router';

export const managerRoutes: Routes = [
  {
    path: '',
    loadComponent: () => import('./dashboard/dashboard.component').then(m => m.DashboardComponent)
  },
  {
    path: 'players',
    loadComponent: () => import('./players/players.component').then(m => m.PlayersComponent)
  },
  {
    path: 'sessions',
    loadComponent: () => import('./sessions/sessions.component').then(m => m.SessionsComponent)
  },
  {
    path: 'attendance-calendar',
    loadComponent: () => import('./attendance-calendar/attendance-calendar.component').then(m => m.AttendanceCalendarComponent)
  },
  {
    path: 'coaches',
    loadComponent: () => import('./coaches/coaches.component').then(m => m.CoachesComponent)
  },
  {
    path: 'fees',
    loadComponent: () => import('./fees/fees.component').then(m => m.FeesComponent)
  },
  {
    path: 'branches',
    loadComponent: () => import('./branches/branches.component').then(m => m.BranchesComponent)
  },
  {
    path: 'monthly-records',
    loadComponent: () => import('./monthly-records/monthly-records.component').then(m => m.MonthlyRecordsComponent)
  },
  {
    path: 'revenue',
    loadComponent: () => import('./revenue/revenue.component').then(m => m.RevenueComponent)
  },
  {
    path: 'settings',
    loadComponent: () => import('./settings/settings.component').then(m => m.SettingsComponent)
  }
];


