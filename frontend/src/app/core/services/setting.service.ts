import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

export interface Setting {
  value: string;
  type: string;
  description?: string;
}

@Injectable({
  providedIn: 'root'
})
export class SettingService {
  private endpoint = '/settings';

  constructor(private apiService: ApiService) {}

  getAllSettings(): Observable<Record<string, Setting>> {
    return this.apiService.get<Record<string, Setting>>(this.endpoint);
  }

  getSetting(key: string): Observable<{ key: string; value: any }> {
    return this.apiService.get<{ key: string; value: any }>(`${this.endpoint}/${key}`);
  }

  updateSetting(key: string, value: any): Observable<Setting> {
    return this.apiService.put<Setting>(`${this.endpoint}/${key}`, { value });
  }

  updatePeriodDates(startDate: string, endDate: string): Observable<any> {
    return this.apiService.post(`${this.endpoint}/period-dates`, {
      period_start_date: startDate,
      period_end_date: endDate
    });
  }
}

