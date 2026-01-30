import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { CoachAttendance, CoachAttendanceFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class CoachAttendanceService {
  private endpoint = '/coach-attendances';

  constructor(private apiService: ApiService) {}

  getAllAttendances(filters?: CoachAttendanceFilters): Observable<CoachAttendance[]> {
    return this.apiService.get<CoachAttendance[]>(this.endpoint, filters);
  }

  getAttendanceById(id: number): Observable<CoachAttendance> {
    return this.apiService.get<CoachAttendance>(`${this.endpoint}/${id}`);
  }

  createAttendance(attendanceData: Partial<CoachAttendance>): Observable<CoachAttendance> {
    return this.apiService.post<CoachAttendance>(this.endpoint, attendanceData);
  }

  updateAttendance(id: number, attendanceData: Partial<CoachAttendance>): Observable<CoachAttendance> {
    return this.apiService.patch<CoachAttendance>(`${this.endpoint}/${id}`, attendanceData);
  }

  deleteAttendance(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}



