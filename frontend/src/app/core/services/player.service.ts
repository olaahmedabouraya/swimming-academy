import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Player, PlayerFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class PlayerService {
  private endpoint = '/players';

  constructor(private apiService: ApiService) {}

  getAllPlayers(filters?: PlayerFilters): Observable<Player[]> {
    return this.apiService.get<Player[]>(this.endpoint, filters);
  }

  getPlayerById(id: number): Observable<Player> {
    return this.apiService.get<Player>(`${this.endpoint}/${id}`);
  }

  getMyProfile(): Observable<Player> {
    return this.apiService.get<Player>(`${this.endpoint}/my-profile`);
  }

  createPlayer(playerData: Partial<Player>): Observable<Player> {
    return this.apiService.post<Player>(this.endpoint, playerData);
  }

  createPlayerWithUser(playerData: any): Observable<Player> {
    return this.apiService.post<Player>(`${this.endpoint}/create-with-user`, playerData);
  }

  updatePlayer(id: number, playerData: Partial<Player>): Observable<Player> {
    return this.apiService.put<Player>(`${this.endpoint}/${id}`, playerData);
  }

  deletePlayer(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }

  moveSession(playerId: number, sessionId: number): Observable<Player> {
    return this.apiService.post<Player>(`${this.endpoint}/${playerId}/move-session`, { session_id: sessionId });
  }

  updateSportsManagerNotes(playerId: number, notes: string): Observable<Player> {
    return this.apiService.post<Player>(`${this.endpoint}/${playerId}/update-sports-manager-notes`, {
      sports_manager_notes: notes
    });
  }

  setExcusedAbsences(playerId: number, allowed: number): Observable<Player> {
    return this.apiService.post<Player>(`${this.endpoint}/${playerId}/set-excused-absences`, {
      excused_absences_allowed: allowed
    });
  }
}
