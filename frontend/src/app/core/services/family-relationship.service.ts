import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

export interface FamilyRelationship {
  id?: number;
  player_id: number;
  related_player_id: number;
  relationship_type: 'sibling' | 'parent';
  discount_percentage: number;
  player?: any;
  related_player?: any;
}

@Injectable({
  providedIn: 'root'
})
export class FamilyRelationshipService {
  private endpoint = '/family-relationships';

  constructor(private apiService: ApiService) {}

  getRelationshipsForPlayer(playerId: number): Observable<FamilyRelationship[]> {
    return this.apiService.get<FamilyRelationship[]>(`${this.endpoint}/player/${playerId}`);
  }

  createRelationship(relationship: Partial<FamilyRelationship>): Observable<FamilyRelationship> {
    return this.apiService.post<FamilyRelationship>(this.endpoint, relationship);
  }

  updateRelationship(id: number, relationship: Partial<FamilyRelationship>): Observable<FamilyRelationship> {
    return this.apiService.put<FamilyRelationship>(`${this.endpoint}/${id}`, relationship);
  }

  deleteRelationship(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}

