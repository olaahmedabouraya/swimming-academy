import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { PlayerRating, RatingFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class RatingService {
  private endpoint = '/ratings';

  constructor(private apiService: ApiService) {}

  getAllRatings(filters?: RatingFilters): Observable<PlayerRating[]> {
    return this.apiService.get<PlayerRating[]>(this.endpoint, filters);
  }

  getRatingById(id: number): Observable<PlayerRating> {
    return this.apiService.get<PlayerRating>(`${this.endpoint}/${id}`);
  }

  createRating(ratingData: Partial<PlayerRating>): Observable<PlayerRating> {
    return this.apiService.post<PlayerRating>(this.endpoint, ratingData);
  }

  updateRating(id: number, ratingData: Partial<PlayerRating>): Observable<PlayerRating> {
    return this.apiService.put<PlayerRating>(`${this.endpoint}/${id}`, ratingData);
  }

  deleteRating(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}

