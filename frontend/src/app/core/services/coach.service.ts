import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Coach, CoachFilters, CoachStats } from '../models';

@Injectable({
  providedIn: 'root'
})
export class CoachService {
  private endpoint = '/coaches';

  constructor(private apiService: ApiService) {}

  getAllCoaches(filters?: CoachFilters): Observable<Coach[]> {
    return this.apiService.get<Coach[]>(this.endpoint, filters);
  }

  getCoachById(id: number): Observable<Coach> {
    return this.apiService.get<Coach>(`${this.endpoint}/${id}`);
  }

  getCoachStats(id: number, startDate?: string, endDate?: string): Observable<CoachStats> {
    const params: any = {};
    if (startDate) params.start_date = startDate;
    if (endDate) params.end_date = endDate;
    return this.apiService.get<CoachStats>(`${this.endpoint}/${id}/stats`, params);
  }

  createCoach(coachData: Partial<Coach>): Observable<Coach> {
    return this.apiService.post<Coach>(this.endpoint, coachData);
  }

  updateCoach(id: number, coachData: Partial<Coach>): Observable<Coach> {
    return this.apiService.put<Coach>(`${this.endpoint}/${id}`, coachData);
  }

  deleteCoach(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }

  assignPlayer(coachId: number, playerId: number): Observable<any> {
    return this.apiService.post(`${this.endpoint}/${coachId}/assign-player`, { player_id: playerId });
  }

  removePlayer(playerId: number): Observable<any> {
    return this.apiService.post(`/players/${playerId}/remove-coach`, {});
  }
}


