import { Component, Input, Output, EventEmitter, OnInit, AfterViewInit, OnDestroy, ViewChild, ElementRef, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import * as L from 'leaflet';

@Component({
  selector: 'app-location-input',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
    <div class="location-input-container">
      <div class="coordinates-input">
        <div class="form-row">
          <div class="form-group">
            <label>Latitude *</label>
            <input 
              type="number" 
              step="any"
              class="form-input"
              [(ngModel)]="manualLatitude"
              (input)="onCoordinatesChange()"
              placeholder="e.g., 31.2001"
              required>
            <small class="form-hint">Range: -90 to 90</small>
          </div>
          <div class="form-group">
            <label>Longitude *</label>
            <input 
              type="number" 
              step="any"
              class="form-input"
              [(ngModel)]="manualLongitude"
              (input)="onCoordinatesChange()"
              placeholder="e.g., 29.9187"
              required>
            <small class="form-hint">Range: -180 to 180</small>
          </div>
        </div>
        
        <!-- Map Display -->
        <div class="map-display-container">
          <div #mapContainer class="map-container"></div>
          <div class="coordinates-display" *ngIf="selectedLatitude() && selectedLongitude()">
            <small>📍 Location: {{ selectedLatitude() | number:'1.6-6' }}, {{ selectedLongitude() | number:'1.6-6' }}</small>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .location-input-container {
      width: 100%;
    }
    .coordinates-input {
      animation: fadeIn 0.3s ease-in;
    }
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    .form-group {
      margin-bottom: 1rem;
    }
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.9);
    }
    .form-input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.95);
      color: #1e293b;
      font-size: 1rem;
    }
    .form-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    .form-hint {
      display: block;
      margin-top: 0.25rem;
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.7);
    }
    .map-display-container {
      margin-top: 1.5rem;
    }
    .map-container {
      width: 100%;
      height: 250px;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.3);
      background: rgba(255, 255, 255, 0.1);
    }
    .coordinates-display {
      margin-top: 0.5rem;
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.875rem;
      text-align: center;
    }
    ::ng-deep .leaflet-container {
      background: rgba(255, 255, 255, 0.95) !important;
    }
    ::ng-deep .leaflet-control-zoom-in,
    ::ng-deep .leaflet-control-zoom-out {
      background-color: rgba(255, 255, 255, 0.9) !important;
      color: #1e293b !important;
      border: none !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }
    ::ng-deep .leaflet-control-attribution {
      display: none !important;
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  `]
})
export class LocationInputComponent implements OnInit, AfterViewInit, OnDestroy {
  @Input() latitude: number | null = null;
  @Input() longitude: number | null = null;
  @Output() locationSelected = new EventEmitter<{ latitude: number; longitude: number }>();

  @ViewChild('mapContainer', { static: false }) mapContainer!: ElementRef;

  selectedLatitude = signal<number | null>(null);
  selectedLongitude = signal<number | null>(null);
  manualLatitude: number | null = null;
  manualLongitude: number | null = null;
  map: L.Map | null = null;
  marker: L.Marker | null = null;

  ngOnInit() {
    if (this.latitude && this.longitude) {
      this.selectedLatitude.set(this.latitude);
      this.selectedLongitude.set(this.longitude);
      this.manualLatitude = this.latitude;
      this.manualLongitude = this.longitude;
    }
  }

  ngAfterViewInit() {
    // Initialize map with default location or provided coordinates
    setTimeout(() => {
      this.initMap();
    }, 100);
  }

  ngOnDestroy() {
    if (this.map) {
      this.map.remove();
    }
  }

  initMap() {
    if (!this.mapContainer) {
      return;
    }

    // Use provided coordinates or default to Cairo, Egypt
    const lat = this.selectedLatitude() ?? 30.0444;
    const lng = this.selectedLongitude() ?? 31.2357;
    const zoom = this.selectedLatitude() && this.selectedLongitude() ? 16 : 10;

    // Remove existing map if any
    if (this.map) {
      this.map.remove();
      this.marker = null;
    }

    // Create map
    this.map = L.map(this.mapContainer.nativeElement).setView([lat, lng], zoom);

    // Add OpenStreetMap tiles without attribution
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '', // Remove attribution
      maxZoom: 19
    }).addTo(this.map);

    // Add marker only if coordinates are provided
    if (this.selectedLatitude() && this.selectedLongitude()) {
      if (this.marker) {
        this.marker.setLatLng([lat, lng]);
      } else {
        this.marker = L.marker([lat, lng]).addTo(this.map);
      }

      // Add popup with coordinates
      this.marker.bindPopup(`
        <div style="text-align: center;">
          <strong>📍 Location</strong><br>
          <small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
        </div>
      `).openPopup();
    }
  }

  onCoordinatesChange() {
    if (this.manualLatitude !== null && this.manualLongitude !== null) {
      // Validate ranges
      const lat = Math.max(-90, Math.min(90, this.manualLatitude));
      const lng = Math.max(-180, Math.min(180, this.manualLongitude));
      
      // Update if changed
      if (lat !== this.manualLatitude) {
        this.manualLatitude = lat;
      }
      if (lng !== this.manualLongitude) {
        this.manualLongitude = lng;
      }
      
      this.selectedLatitude.set(lat);
      this.selectedLongitude.set(lng);
      
      // Update map
      if (this.map) {
        // Update marker or create if it doesn't exist
        if (this.marker) {
          this.marker.setLatLng([lat, lng]);
        } else {
          this.marker = L.marker([lat, lng]).addTo(this.map);
        }
        
        // Center map on new location with appropriate zoom
        this.map.setView([lat, lng], 16);
        
        // Update popup
        this.marker.bindPopup(`
          <div style="text-align: center;">
            <strong>📍 Location</strong><br>
            <small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
          </div>
        `).openPopup();
      } else if (this.mapContainer) {
        // Initialize map if not already created
        setTimeout(() => {
          this.initMap();
        }, 100);
      }
      
      this.locationSelected.emit({ latitude: lat, longitude: lng });
    }
  }
}
