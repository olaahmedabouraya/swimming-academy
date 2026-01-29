import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';
import { managerGuard } from './core/guards/manager.guard';

export const routes: Routes = [
  {
    path: '',
    redirectTo: '/login',
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadComponent: () => import('./features/auth/login/login.component').then(m => m.LoginComponent)
  },
  {
    path: 'register',
    loadComponent: () => import('./features/auth/register/register.component').then(m => m.RegisterComponent)
  },
  {
    path: 'player',
    canActivate: [authGuard],
    loadChildren: () => import('./features/player/player.routes').then(m => m.playerRoutes)
  },
  {
    path: 'manager',
    canActivate: [authGuard, managerGuard],
    loadChildren: () => import('./features/manager/manager.routes').then(m => m.managerRoutes)
  },
  {
    path: '**',
    redirectTo: '/login'
  }
];


