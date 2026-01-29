import { Component, Input, AfterViewInit, OnDestroy, ViewChild, ElementRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import * as L from 'leaflet';

@Component({
  selector: 'app-location-display',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="location-display-container">
      <div #mapContainer class="map-container"></div>
      <div class="coordinates-display" *ngIf="latitude && longitude">
        <small>📍 {{ latitude | number:'1.4-4' }}, {{ longitude | number:'1.4-4' }}</small>
      </div>
    </div>
  `,
  styles: [`
    .location-display-container {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .map-container {
      width: 100%;
      height: 100%;
      min-height: 200px;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.3);
      background: rgba(255, 255, 255, 0.1);
    }
    .coordinates-display {
      margin-top: 0.5rem;
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.85rem;
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
      font-size: 18px !important;
      line-height: 30px !important;
      width: 30px !important;
      height: 30px !important;
    }
    ::ng-deep .leaflet-control-attribution {
      display: none !important;
    }
  `]
})
export class LocationDisplayComponent implements AfterViewInit, OnDestroy {
  @Input() latitude: number | null = null;
  @Input() longitude: number | null = null;

  @ViewChild('mapContainer', { static: false }) mapContainer!: ElementRef;

  map: L.Map | null = null;
  marker: L.Marker | null = null;

  ngAfterViewInit() {
    if (this.latitude && this.longitude) {
      setTimeout(() => {
        this.initMap();
      }, 100);
    }
  }

  ngOnDestroy() {
    if (this.map) {
      this.map.remove();
    }
  }

  initMap() {
    if (!this.mapContainer || !this.latitude || !this.longitude) {
      return;
    }

    const lat = this.latitude;
    const lng = this.longitude;

    // Remove existing map if any
    if (this.map) {
      this.map.remove();
      this.marker = null;
    }

    // Create map
    this.map = L.map(this.mapContainer.nativeElement).setView([lat, lng], 15);

    // Add OpenStreetMap tiles without attribution
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '',
      maxZoom: 19
    }).addTo(this.map);

    // Add marker
    this.marker = L.marker([lat, lng]).addTo(this.map);

    // Add popup with coordinates
    this.marker.bindPopup(`
      <div style="text-align: center;">
        <strong>📍 Location</strong><br>
        <small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
      </div>
    `);
  }
}

