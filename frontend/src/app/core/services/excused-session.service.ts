import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { ExcusedSession, ExcusedSessionFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class ExcusedSessionService {
  private endpoint = '/excused-sessions';

  constructor(private apiService: ApiService) {}

  getAllExcusedSessions(filters?: ExcusedSessionFilters): Observable<ExcusedSession[]> {
    return this.apiService.get<ExcusedSession[]>(this.endpoint, filters);
  }

  getExcusedSessionById(id: number): Observable<ExcusedSession> {
    return this.apiService.get<ExcusedSession>(`${this.endpoint}/${id}`);
  }

  getPendingForPlayer(playerId: number): Observable<ExcusedSession[]> {
    return this.apiService.get<ExcusedSession[]>(`${this.endpoint}/pending/${playerId}`);
  }

  createExcusedSession(sessionData: Partial<ExcusedSession>): Observable<ExcusedSession> {
    return this.apiService.post<ExcusedSession>(this.endpoint, sessionData);
  }

  markMakeupTaken(id: number, makeupAttendanceId: number, makeupSessionId: number, makeupDate: string): Observable<ExcusedSession> {
    return this.apiService.post<ExcusedSession>(`${this.endpoint}/${id}/mark-makeup`, {
      makeup_attendance_id: makeupAttendanceId,
      makeup_session_id: makeupSessionId,
      makeup_date: makeupDate
    });
  }

  markAsDiscounted(id: number, feeId: number): Observable<ExcusedSession> {
    return this.apiService.post<ExcusedSession>(`${this.endpoint}/${id}/mark-discounted`, {
      fee_id: feeId
    });
  }

  deleteExcusedSession(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}



