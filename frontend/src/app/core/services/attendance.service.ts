import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Attendance, AttendanceFilters } from '../models';
import { TrainingSession } from '../models';

@Injectable({
  providedIn: 'root'
})
export class AttendanceService {
  private endpoint = '/attendances';

  constructor(private apiService: ApiService) {}

  getAllAttendances(filters?: AttendanceFilters): Observable<Attendance[]> {
    return this.apiService.get<Attendance[]>(this.endpoint, filters);
  }

  getAttendanceById(id: number): Observable<Attendance> {
    return this.apiService.get<Attendance>(`${this.endpoint}/${id}`);
  }

  getSessionsForDate(date: string, branchId?: number): Observable<TrainingSession[]> {
    const params: any = { date };
    if (branchId) params.branch_id = branchId;
    return this.apiService.get<TrainingSession[]>(`${this.endpoint}/sessions-for-date`, params);
  }

  getPlayersForSession(sessionId: number, date: string): Observable<any[]> {
    return this.apiService.get<any[]>(`${this.endpoint}/players-for-session/${sessionId}`, { date });
  }

  createAttendance(attendanceData: Partial<Attendance>): Observable<Attendance> {
    return this.apiService.post<Attendance>(this.endpoint, attendanceData);
  }

  markMultiple(attendances: Partial<Attendance>[]): Observable<Attendance[]> {
    return this.apiService.post<Attendance[]>(`${this.endpoint}/mark-multiple`, { attendances });
  }

  updateAttendance(id: number, attendanceData: Partial<Attendance>): Observable<Attendance> {
    return this.apiService.put<Attendance>(`${this.endpoint}/${id}`, attendanceData);
  }

  deleteAttendance(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}
