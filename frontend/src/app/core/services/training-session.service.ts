import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { TrainingSession, TrainingSessionFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class TrainingSessionService {
  private endpoint = '/training-sessions';

  constructor(private apiService: ApiService) {}

  getAllSessions(filters?: TrainingSessionFilters): Observable<TrainingSession[]> {
    return this.apiService.get<TrainingSession[]>(this.endpoint, filters);
  }

  getSessionById(id: number): Observable<TrainingSession> {
    return this.apiService.get<TrainingSession>(`${this.endpoint}/${id}`);
  }

  getSessionsForDate(date: string, branchId?: number): Observable<TrainingSession[]> {
    const params: any = { date };
    if (branchId) params.branch_id = branchId;
    return this.apiService.get<TrainingSession[]>(`${this.endpoint}/for-date`, params);
  }

  createSession(sessionData: Partial<TrainingSession>): Observable<TrainingSession> {
    return this.apiService.post<TrainingSession>(this.endpoint, sessionData);
  }

  updateSession(id: number, sessionData: Partial<TrainingSession>): Observable<TrainingSession> {
    return this.apiService.put<TrainingSession>(`${this.endpoint}/${id}`, sessionData);
  }

  deleteSession(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}



