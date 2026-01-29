import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { MonthlyRecord, MonthlyRecordFilters, MonthlyStatistics } from '../models';

@Injectable({
  providedIn: 'root'
})
export class MonthlyRecordService {
  private endpoint = '/monthly-records';

  constructor(private apiService: ApiService) {}

  getAllRecords(filters?: MonthlyRecordFilters): Observable<MonthlyRecord[]> {
    return this.apiService.get<MonthlyRecord[]>(this.endpoint, filters);
  }

  getRecordById(id: number): Observable<MonthlyRecord> {
    return this.apiService.get<MonthlyRecord>(`${this.endpoint}/${id}`);
  }

  createRecord(recordData: Partial<MonthlyRecord>): Observable<MonthlyRecord> {
    return this.apiService.post<MonthlyRecord>(this.endpoint, recordData);
  }

  updateRecord(id: number, recordData: Partial<MonthlyRecord>): Observable<MonthlyRecord> {
    return this.apiService.put<MonthlyRecord>(`${this.endpoint}/${id}`, recordData);
  }

  deleteRecord(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }

  getStatistics(filters?: MonthlyRecordFilters): Observable<MonthlyStatistics> {
    return this.apiService.get<MonthlyStatistics>(`${this.endpoint}/statistics`, filters);
  }
}

