import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-logo',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="logo-container">
      <div class="logo-icon">
        <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
          <!-- Outer circle -->
          <circle cx="60" cy="60" r="55" fill="none" stroke="#1e3a8a" stroke-width="4"/>
          
          <!-- Main flame body -->
          <path d="M 35 60 Q 60 25, 85 60 Q 60 95, 35 60" fill="#3b82f6" opacity="0.9"/>
          <path d="M 40 60 Q 60 35, 80 60 Q 60 85, 40 60" fill="#60a5fa" opacity="0.95"/>
          <path d="M 45 60 Q 60 45, 75 60 Q 60 75, 45 60" fill="#93c5fd" opacity="1"/>
          
          <!-- Flame sparks/droplets -->
          <circle cx="60" cy="30" r="4" fill="#93c5fd"/>
          <circle cx="55" cy="35" r="3" fill="#bfdbfe"/>
          <circle cx="65" cy="35" r="3" fill="#bfdbfe"/>
          <circle cx="58" cy="40" r="2" fill="#dbeafe"/>
          <circle cx="62" cy="40" r="2" fill="#dbeafe"/>
        </svg>
      </div>
      <div class="logo-text">
        <h1 class="logo-title">OLYMPIA</h1>
        <p class="logo-subtitle">SPORTS ACADEMY</p>
      </div>
    </div>
  `,
  styles: [`
    .logo-container {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo-icon {
      width: 70px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: pulse 2s ease-in-out infinite;
    }

    .logo-icon svg {
      width: 100%;
      height: 100%;
      filter: drop-shadow(0 4px 8px rgba(59, 130, 246, 0.3));
    }

    .logo-text {
      display: flex;
      flex-direction: column;
    }

    .logo-title {
      font-size: 32px;
      font-weight: 800;
      color: #60a5fa;
      margin: 0;
      line-height: 1;
      letter-spacing: 2px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .logo-subtitle {
      font-size: 11px;
      font-weight: 500;
      color: #ffffff;
      margin: 2px 0 0 0;
      line-height: 1;
      letter-spacing: 3px;
      text-transform: uppercase;
      opacity: 0.95;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }

    @media (max-width: 768px) {
      .logo-icon {
        width: 55px;
        height: 55px;
      }

      .logo-title {
        font-size: 26px;
      }

      .logo-subtitle {
        font-size: 9px;
      }
    }
  `]
})
export class LogoComponent {}
