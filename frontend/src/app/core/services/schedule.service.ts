import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Schedule, ScheduleFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class ScheduleService {
  private endpoint = '/schedules';

  constructor(private apiService: ApiService) {}

  getAllSchedules(filters?: ScheduleFilters): Observable<Schedule[]> {
    return this.apiService.get<Schedule[]>(this.endpoint, filters);
  }

  getScheduleById(id: number): Observable<Schedule> {
    return this.apiService.get<Schedule>(`${this.endpoint}/${id}`);
  }

  createSchedule(scheduleData: Partial<Schedule>): Observable<Schedule> {
    return this.apiService.post<Schedule>(this.endpoint, scheduleData);
  }

  updateSchedule(id: number, scheduleData: Partial<Schedule>): Observable<Schedule> {
    return this.apiService.put<Schedule>(`${this.endpoint}/${id}`, scheduleData);
  }

  deleteSchedule(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}

