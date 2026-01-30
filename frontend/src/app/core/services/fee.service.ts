import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Fee, FeeFilters } from '../models';

@Injectable({
  providedIn: 'root'
})
export class FeeService {
  private endpoint = '/fees';

  constructor(private apiService: ApiService) {}

  getAllFees(filters?: FeeFilters): Observable<Fee[]> {
    return this.apiService.get<Fee[]>(this.endpoint, filters);
  }

  getFeeById(id: number): Observable<Fee> {
    return this.apiService.get<Fee>(`${this.endpoint}/${id}`);
  }

  getRevenue(startDate?: string, endDate?: string): Observable<{ total_revenue: number }> {
    const params: any = {};
    if (startDate) params.start_date = startDate;
    if (endDate) params.end_date = endDate;
    return this.apiService.get<{ total_revenue: number }>(`${this.endpoint}/revenue`, params);
  }

  createFee(feeData: Partial<Fee>): Observable<Fee> {
    return this.apiService.post<Fee>(this.endpoint, feeData);
  }

  deleteFee(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }

  recordWithdrawal(playerId: number, amount: number, paymentDate: string, notes?: string): Observable<Fee> {
    return this.apiService.post<Fee>(`${this.endpoint}/withdrawal`, {
      player_id: playerId,
      amount,
      payment_date: paymentDate,
      notes
    });
  }

  createRenewalWithDiscounts(playerId: number, baseAmount: number, paymentDate: string, notes?: string): Observable<Fee> {
    return this.apiService.post<Fee>(`${this.endpoint}/renewal-with-discounts`, {
      player_id: playerId,
      base_amount: baseAmount,
      payment_date: paymentDate,
      notes
    });
  }
}



